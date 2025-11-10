<?php

namespace App\Http\Controllers;

use App\Models\ElectionResult;
use Illuminate\Http\Request;

class ElectionResultController extends Controller
{
    public function index()
    {
        $results = ElectionResult::all();
        return view('allPhotos', compact('results'));
    }

   private function recalcTotalAndPercentages()
{
    $allResults = ElectionResult::where('party_name', '!=', 'Total')->get();
    $totalSeats = $allResults->sum('seats_won');

    foreach ($allResults as $party) {
        $party->percentage = $totalSeats > 0 ? round(($party->seats_won / $totalSeats) * 100, 2) : 0;
        $party->save();
    }
}

 


    public function add()
    {
        return view('admin.addElectionData');
    }

   public function save(Request $request)
    {
        $request->validate([
            'party_name'   => 'required|string|max:255',
            'abbreviation' => 'required|string|max:10',
            'alliance'     => 'required|string|max:50',
            'seats_won'    => 'required|integer|min:0',
        ]);
        ElectionResult::create([
            'party_name'        => $request->party_name,
            'abbreviation'      => $request->abbreviation,
            'alliance'          => $request->alliance,
            'seats_won'         => $request->seats_won,
            'show_in_list'      => $request->show_in_list ?? 1,
            'show_in_highlight' => $request->show_in_highlight ?? 1,
        ]);


        // Get current total
        // $currentTotal = ElectionResult::sum('seats_won');
        // $newTotal = $currentTotal + $request->seats_won;

        // Check constraint (243 max)
        // if ($newTotal > 243) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'Total seats cannot exceed 243. Currently available seats left: ' . (243 - $currentTotal));
        // }
    ElectionResult::create($request->only('party_name','abbreviation','alliance','seats_won'));

    // Recalculate totals and percentages
    $this->recalcTotalAndPercentages();

    return redirect()->back()->with('success', 'Election data added successfully!');
}


    public function showresults()
    {
        $results = ElectionResult::select(
                'id',
                'party_name',
                'abbreviation',
                'alliance',
                'seats_won',
                'percentage'
            )
            ->paginate(20);

        $results->setPath(asset('/election-results'));

       // calculate total seats (for all records, not just paginated page)
    $totalSeats = ElectionResult::sum('seats_won');

    return view('admin.showElectionResults', compact('results', 'totalSeats'));
        
    }
    // Show edit form
public function edit($id)
{
    $result = ElectionResult::findOrFail($id);
    return view('admin.editElectionResults', compact('result'));
}

public function update(Request $request, $id)
{
    $result = ElectionResult::findOrFail($id);

    $request->validate([
        'party_name'   => 'required|string|max:255',
        'abbreviation' => 'required|string|max:10',
        'alliance'     => 'required|string|max:50',
        'seats_won'    => 'required|integer|min:0',

        
    ]);

        // $currentTotal = ElectionResult::sum('seats_won');
        // $newTotal = ($currentTotal - $result->seats_won) + $request->seats_won;
        
        // if ($newTotal > 243) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'Total seats cannot exceed 243. Currently available seats left: ' . (243 - ($currentTotal - $result->seats_won)));
        // }
        $result->update([
            'party_name'        => $request->party_name,
            'abbreviation'      => $request->abbreviation,
            'alliance'          => $request->alliance,
            'seats_won'         => $request->seats_won,
            'show_in_list'      => $request->show_in_list ?? 1,
            'show_in_highlight' => $request->show_in_highlight ?? 1,
        ]);

    // Recalculate totals and percentages
    $this->recalcTotalAndPercentages();

    return redirect()->route('showElectionResults')->with('success', 'Election data updated successfully!');
    }
    public function addParty()
    {
        // Show the add party form
        return view('admin.addPartyDetails');
    }

    public function saveParty(Request $request)
{
    $request->validate([
        'party_name'   => 'required|string|max:255',
        'abbreviation' => 'required|string|max:10',
        'alliance'     => 'required|string|max:50',
        'party_logo'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $logoPath = null;

    if ($request->hasFile('party_logo')) {
        // Store directly in public/party_logos
        $image = $request->file('party_logo');
        $fileName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('election-widget'), $fileName);
        $logoPath = 'election-widget/' . $fileName;  // relative path for asset()
    }

    ElectionResult::create([
        'party_name'   => $request->party_name,
        'abbreviation' => $request->abbreviation,
        'alliance'     => $request->alliance,
        'party_logo'   => $logoPath,
    ]);

    return redirect()->back()->with('success', 'Party details added successfully!');
}



// Delete election result
public function delete($id)
{
    $result = ElectionResult::findOrFail($id);
    $result->delete();

    // Recalculate totals and percentages
    $this->recalcTotalAndPercentages();

    return redirect()->route('showElectionResults')->with('success', 'Election data deleted successfully!');
}

    public function manageVoteCount()
{
    // Fetch only parties marked for left side table
    $results = ElectionResult::where('show_in_list', -1)->get();

    return view('admin.manageVoteCount', compact('results'));
}

public function saveVoteCount(Request $request)
{
    // Fetch parties to update
    $parties = ElectionResult::where('show_in_list', -1)->get();

    foreach ($parties as $party) {
        $abbr = strtolower($party->abbreviation); // for input field name

        // Get values from request, default to 0 if not provided
        $w_l = $request->input($abbr.'_wl', 0);
        $w   = $request->input($abbr.'_w', 0);
        $l   = $request->input($abbr.'_l', 0);

        $party->seats_won  = $w;
        $party->seat_loss  = $l;
        $party->total_seats = $w_l;
        $party->save();
    }

    $totalSeats = 0;  //total of seats_won

    // Step 1: Calculate new total (sum of all submitted seat values)
    foreach ($parties as $party) {
        $inputName = 'seat_' . strtolower($party->abbreviation);
        $seatCount = (int) $request->input($inputName, $party->seats_won);
        $totalSeats += $seatCount;
    }

    // Step 2: Check total limit
    if ($totalSeats > 243) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'You have exceeded by ' . ($totalSeats - 243) . ' seats.');

    }

    // Step 3: Save data if within limit
    foreach ($parties as $party) {
        $inputName = 'seat_' . strtolower($party->abbreviation);

        if ($request->has($inputName)) {
            $party->seats_won = $request->input($inputName);
            $party->save();
        }
    }
try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
             Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
   // return redirect()->back()->with('success', 'Vote counts updated successfully!');
    return redirect(config('global.base_url').'election/manage-vote-count')->with('success', 'Vote counts updated successfully!');
}

public function manageSeats()
{
    // Fetch only parties marked for left side table
    $parties = ElectionResult::where('show_in_highlight', -1)->get();

    return view('admin.topPartySeats', compact('parties'));
}

public function saveTopSeats(Request $request)
{
    // Fetch parties with show_in_highlight = -1
    $parties = ElectionResult::where('show_in_highlight', -1)->get();

    $totalSeats = 0;

    // Step 1: Calculate new total (sum of all submitted seat values)
    foreach ($parties as $party) {
        $inputName = 'seat_' . strtolower($party->abbreviation);
        $seatCount = (int) $request->input($inputName, $party->seats_won);
        $totalSeats += $seatCount;
    }

    // Step 2: Check total limit
    if ($totalSeats > 243) {
        return redirect()->back()
            ->withInput()
            ->with('error', '   You have exceeded by ' . ($totalSeats - 243) . ' seats.');

    }

    // Step 3: Save data if within limit
    foreach ($parties as $party) {
        $inputName = 'seat_' . strtolower($party->abbreviation);
        
        // --- NEW ---
        // Get the name for the sequence input
        $sequenceInputName = 'sequence_' . strtolower($party->abbreviation);
        // --- END NEW ---

        if ($request->has($inputName)) {
            
            // --- MODIFIED ---
            $party->seats_won = $request->input($inputName);
            
            // Check if the sequence input was submitted and update it
            if ($request->has($sequenceInputName)) {
                 $party->sequence = $request->input($sequenceInputName);
            }
            
            $party->save();
            // --- END MODIFIED ---
        }
    }

    // --- MODIFIED ---
    try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
     return redirect(config('global.base_url').'election/manage-top-party-seats')->with('success', 'Seats and Sequence updated successfully!');
    // return redirect()->back()->with('success', ' Seats and sequence updated successfully!');
}

// public function voteCountList()
// {
//     // Fetch parties that are active in list view
//     $results = ElectionResult::where('show_in_list', -1)->orderBy('id', 'asc')->get();

//     // Pass data to Blade
//     return view('voteCountList', compact('results'));
// }

public function exitpoll()
    {
        // Fetch only parties marked for left side table
        // --- MODIFIED ---
        // Added orderBy('sequence', 'asc') to sort the list
        $results = ElectionResult::where('show_in_list', -1)
                            ->orderBy('sequence', 'asc')
                            ->get();
        // --- END MODIFIED ---
        
        echo "coming here";
        return view('admin.exitpoll', compact('results'));
    }

  public function exitpollsave(Request $request)
{
    // Fetch parties to update
    $parties = ElectionResult::where('show_in_list', -1)->get();
 
    foreach ($parties as $party) {
        $abbr = strtolower($party->abbreviation); // for input field name
 
        // Get values from request, default to 0 if not provided
        $exitpoll = $request->input($abbr.'_wl', 0);
        $party->exit_poll = $exitpoll;
        $party->save();
    }
 
    $totalSeats = 0;  //total of seats_won
 
    // Step 1: Calculate new total (sum of all submitted seat values)
    foreach ($parties as $party) {
        $inputName = 'seat_' . strtolower($party->abbreviation);
        $seatCount = (int) $request->input($inputName, $party->exit_poll);
        $totalSeats += $seatCount;
    }
 
    // Step 2: Check total limit
    if ($totalSeats > 243) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'You have exceeded by ' . ($totalSeats - 243) . ' seats.');
 
    }
 
    // Step 3: Save data if within limit
    foreach ($parties as $party) {
        $inputName = 'seat_' . strtolower($party->abbreviation);
 
        if ($request->has($inputName)) {
            $party->exit_poll = $request->input($inputName);
            $party->save();
        }
    }
     try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
             Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
       return redirect(config('global.base_url').'election/exit-poll')->with('success', 'Seats updated successfully!');
       //return redirect()->back()->with('success', ' Seats updated successfully!');
}


}

