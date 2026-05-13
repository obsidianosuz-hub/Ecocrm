<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Task;
use App\Models\User;
use App\Models\Transaction;

class ChatController extends Controller
{
    public function index()
    {
        // Note: Task expiration and fines are now handled by the Scheduler in routes/console.php

        $messages = Message::with('sender')
            ->whereNull('recipient_id')
            ->orWhere('recipient_id', auth()->id())
            ->orWhere('sender_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(50)->get()->reverse();
        $users = User::where('id', '!=', auth()->id())->get(); // For task assignment dropdown
        
        if (auth()->user()->role === 'admin') {
            $tasks = Task::with(['assigner', 'assignee'])->orderBy('created_at', 'desc')->get();
        } else {
            $tasks = Task::with(['assigner'])->where('assigned_to', auth()->id())->orderBy('created_at', 'desc')->get();
        }

        return view('dashboards.chat', compact('messages', 'users', 'tasks'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:20480'
        ]);

        if (!$request->message && !$request->hasFile('attachment')) {
            return redirect()->back()->withErrors('Message or file is required.');
        }

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('chat_files', 'public');
        }

        Message::create([
            'sender_id' => auth()->id(),
            'message' => $request->message ?? '',
            'file_path' => $filePath
        ]);

        return redirect()->back();
    }
    
    public function assignTask(Request $request)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'cashier') {
            abort(403, 'Faqat Admin va Kassir vazifa bera oladi.');
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after:now',
            'fine_amount' => 'required|numeric|min:0',
            'reward_amount' => 'required|numeric|min:0',
            'xp_reward' => 'required|integer|min:0',
        ]);
        
        $task = Task::create([
            'assigned_by' => auth()->id(),
            'assigned_to' => $request->assigned_to,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'fine_amount' => $request->fine_amount,
            'reward_amount' => $request->reward_amount,
            'xp_reward' => $request->xp_reward,
            'status' => 'pending'
        ]);
        
        $user = User::find($request->assigned_to);
        Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $request->assigned_to,
            'message' => "NEW MISSION ASSIGNED TO {$user->name}: {$task->title}. Deadline: {$task->deadline}. Reward: " . number_format($task->reward_amount) . " UZS / {$task->xp_reward} XP. Penalty: " . number_format($task->fine_amount) . " UZS."
        ]);
        
        return redirect()->back()->with('success', 'Vazifa muvaffaqiyatli topshirildi.');
    }
    
    public function submitTask(Request $request, Task $task)
    {
        if ($task->assigned_to !== auth()->id()) {
            abort(403, 'Ushbu topshiriq faqat uni qabul qilib olgan xodim tomonidan tasdiqlanishi va yakunlanishi mumkin. Siz kuzatuvchisiz.');
        }
        
        $request->validate([
            'proof_file' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:10240',
            'status' => 'required|in:done,in_progress,failed'
        ]);

        if ($request->status === 'failed') {
            $task->update([
                'status' => 'failed',
                'extension_reason' => $request->extension_reason ?? 'Bajarilmadi deb belgilandi.'
            ]);
            
            Message::create([
                'sender_id' => auth()->id(),
                'recipient_id' => $task->assigned_by,
                'message' => "MISSION FAILED: '{$task->title}' could not be completed. Reason: " . ($request->extension_reason ?? 'Undefined.')
            ]);

            return redirect()->back()->with('error', 'Vazifa bajarilmagan deb belgilandi.');
        }

        if ($request->status === 'in_progress') {
            $task->update([
                'extension_requested' => true,
                'extension_reason' => $request->extension_reason ?? 'Muddatni uzaytirish so\'ralmoqda.',
                'status' => 'extension_pending'
            ]);
            
            Message::create([
                'sender_id' => auth()->id(),
                'recipient_id' => $task->assigned_by,
                'message' => "EXTENSION REQUEST: Project '{$task->title}' needs more time. Reason: " . ($request->extension_reason ?? 'Undefined.')
            ]);

            return redirect()->back()->with('success', 'Muddat uzaytirish so\'rovi yuborildi.');
        }

        if ($request->status === 'done' && !$request->hasFile('proof_file')) {
            return redirect()->back()->withErrors(['proof_file' => 'Bajarilganligini tasdiqlovchi fayl yuklash majburiy.']);
        }

        if ($request->hasFile('proof_file')) {
            $filePath = $request->file('proof_file')->store('task_proofs', 'public');
            $task->update([
                'status' => 'awaiting_verification',
                'proof_file' => $filePath
            ]);
        } else {
            $task->update(['status' => 'awaiting_verification']);
        }
        
        Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $task->assigned_by,
            'message' => "MISSION SUBMITTED: '{$task->title}' is ready for verification. Proof uploaded."
        ]);
        
        return redirect()->back()->with('success', 'Vazifa tekshiruvga yuborildi.');
    }

    public function verifyTask(Request $request, Task $task)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'cashier') abort(403);
        
        $request->validate(['action' => 'required|in:approve,reject,extend']);

        if ($request->action === 'approve') {
            $task->update(['status' => 'completed']);
            
            $user = User::find($task->assigned_to);
            if ($user) {
                $user->xp += $task->xp_reward > 0 ? $task->xp_reward : 50;
                $user->salary += $task->reward_amount;
                $user->save();
                
                // Track Reward Transaction
                if ($task->reward_amount > 0) {
                    Transaction::create([
                        'user_id' => auth()->id(),
                        'type' => 'expense',
                        'amount' => $task->reward_amount,
                        'description' => "REWARD FOR TASK: {$task->title} (Staff: {$user->name})",
                        'company_id' => $user->company_id,
                        'payment_method' => 'cash'
                    ]);
                }
            }

            Message::create([
                'sender_id' => auth()->id(),
                'recipient_id' => $task->assigned_to,
                'message' => "MISSION VERIFIED: '{$task->title}' has been APPROVED. Bonus distributed."
            ]);
        } elseif ($request->action === 'extend') {
            $request->validate(['new_deadline' => 'required|date|after:now']);
            if (!$task->original_deadline) $task->original_deadline = $task->deadline;
            $task->update([
                'deadline' => $request->new_deadline,
                'status' => 'pending',
                'extension_requested' => false
            ]);
            
            Message::create([
                'sender_id' => auth()->id(),
                'recipient_id' => $task->assigned_to,
                'message' => "TIMELINE EXTENDED: '{$task->title}' new deadline set to {$request->new_deadline}."
            ]);
        } else {
            $task->update(['status' => 'pending', 'extension_requested' => false]);
            Message::create([
                'sender_id' => auth()->id(),
                'recipient_id' => $task->assigned_to,
                'message' => "MISSION REJECTED: '{$task->title}' requires revision."
            ]);
        }

        return redirect()->back()->with('success', 'Vazifa holati yangilandi.');
    }

    public function clear()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        Message::truncate();
        return redirect()->back()->with('success', 'Chat tarixi tozalandi.');
    }

    public function deleteMessage(Message $message)
    {
        if (auth()->user()->role !== 'admin' && $message->sender_id !== auth()->id()) abort(403);
        $message->delete();
        return redirect()->back()->with('success', 'Xabar o\'chirildi.');
    }

    public function editMessage(Request $request, Message $message)
    {
        if (auth()->user()->role !== 'admin' && $message->sender_id !== auth()->id()) abort(403);
        
        $request->validate([
            'message' => 'required|string',
        ]);
        
        $message->update(['message' => $request->message . ' (tahrirlandi)']);
        return redirect()->back()->with('success', 'Xabar tahrirlandi.');
    }

    public function deleteTask(Task $task)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $task->delete();
        return redirect()->back()->with('success', 'Vazifa o\'chirildi.');
    }

    public function editTask(Request $request, Task $task)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'fine_amount' => 'required|numeric|min:0',
            'xp_reward' => 'required|integer|min:0',
        ]);
        
        $task->update([
            'assigned_to' => $request->assigned_to,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'fine_amount' => $request->fine_amount,
            'xp_reward' => $request->xp_reward,
        ]);
        
        return redirect()->back()->with('success', 'Vazifa tahrirlandi.');
    }
}
