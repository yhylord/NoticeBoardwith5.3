<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use App\Vote;
use App\Ticket;
use App\Answer;

class VoteVerify
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// general Checking
		if (empty($request->ticket) && !Auth::check()) {
			return redirect('/login')->withErrors(['warning' => Lang::get('login.login_required', [
				'process' => 'vote'
			]),]); // No ticket or user need to login to vote.
		}

		if (empty($vote = Vote::find($request->id))) { //check if vote exists
			return redirect('/error/custom')->withErrors(['warning' => Lang::get('vote.vote_no_found')]); // Vote No Found
		}

		if (strtotime($vote->ended_at) - strtotime('now') < 0) {
			return redirect('/error/custom')->withErrors(['warning' => Lang::get('vote.vote_expired')]); // Vote Expired
		}

		if (strtotime($vote->started_at) - strtotime('now') > 0) {
			return redirect('/error/custom')->withErrors(['warning' => Lang::get('vote.vote_not_started')]); // Vote not started
		}

		// Categorize

		// If user use ticket to vote, then go with this check
		if (!empty($ticket = Ticket::ticket($request->ticket)) && ($vote->type == 1 || $vote->type == 2)) {
			if ($ticket->active == 1) { // check if ticket is valid
				if (!$ticket->isTicketUsed($vote->id)) {
					$request->merge(['type' => 'Ticket']); //将该请求归类到Ticket类型
					return $next($request);
				}
				return redirect('/error/custom')->withErrors(['warning' => Lang::get('vote.ticket_is_used')]); // Ticket is used !
			}
			return redirect('/error/custom')->withErrors(['warning' => Lang::get('vote.ticket_invalid')]); // Ticket Not Valid !
		}

		// If user login to vote, then go with this check
		if ($user = Auth::user() && ($vote->type == 0 || $vote->type == 2)) {
			if (!$user->isUserVoted($vote->id)) {
				$request->merge(['type' => 'User']); //将该请求归类到User类型
				return $next($request);
			}
			return redirect('/error/custom')->withErrors(['warning' => Lang::get('vote.user_has_voted')]); // User has voted !
		}

		return redirect('/error/custom')->withErrors(['warning' => Lang::get('vote.credential_error')]); // Credential invalid
	}
}
