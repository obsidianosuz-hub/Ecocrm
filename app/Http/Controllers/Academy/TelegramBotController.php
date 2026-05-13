<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\TelegramBot;
use Illuminate\Http\Request;

class TelegramBotController extends Controller
{
    public function index()
    {
        $bots = TelegramBot::forCompany()->get();
        return view('academy.telegram_bots.index', compact('bots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bot_token' => 'required|string|max:255',
            'chat_id' => 'required|string|max:255',
        ]);

        TelegramBot::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'bot_token' => $request->bot_token,
            'chat_id' => $request->chat_id,
        ]);

        return redirect()->route('admin.academy.telegram_bots.index')->with('success', 'Telegram Bot muvaffaqiyatli saqlandi!');
    }

    public function destroy(TelegramBot $telegramBot)
    {
        $telegramBot->delete();
        return redirect()->route('admin.academy.telegram_bots.index')->with('success', 'Bot o\'chirildi.');
    }
}
