<?php

namespace App\Http\Controllers;

use App\Http\Middleware\NotSuspended;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;


class ListingController extends Controller implements HasMiddleware
{
    public static function middleware(){
        return [
            new Middleware(['auth', 'verified', NotSuspended::class], // Can call the custom middleware NotSuspended
            except:['index', 'show'])
        ];
    }

    public function index(Request $request)
    {

        $listings  = Listing::whereHas('user', function (Builder $query){
                $query->where('role', '!=', 'suspended' ); // Fetch listing of user who is not suspended
             })
                ->with('user')
                ->where('approved', true) // Only show approved listings
                ->filter(request(['search', 'user_id', 'tag'])) //scope filter from listing model
                ->latest()
                ->paginate(6)
                ->withQueryString();

        return inertia('Home', [
            'listings' => $listings,
            'searchTerm' => $request->search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Listing::class);
        return inertia('Listing/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $newTags = explode(',', $request->tags); // Split tags
        // $newTags = array_map('trim', $newTags); // Remove leading/trailing spaces
        // $newTags = array_filter($newTags); // Remove empty tags
        // $newTags = array_unique($newTags); // Remove duplicate tags
        // $newTags = implode(',', $newTags); // Rejoin tags

        Gate::authorize('create', Listing::class);
        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'desc' => ['required'],
            'tags' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'link' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'max:3072', 'mimes:jpeg,jpg,png,webp'],
        ]);

        if($request->hasFile('image')) {
            $fields['image'] = Storage::disk('public')->put('/images/listing', $request->image); // save image to storage
        }

        // Fixing tags format before storing in database
        $fields['tags'] = implode(',', array_unique(array_filter(array_map('trim', explode(',',  $request->tags)))));

        $request->user()->listings()->create($fields);

        return redirect()->route('dashboard')->with('status', 'Listing created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        // Calling Listing Policy, view method using Gate Facade
        Gate::authorize('view', $listing);

        return inertia('Listing/Show', [
            "listing" => $listing,
            "user" => $listing->user->only(['name', 'id']), // passed name and id only
            "canModify" => auth()->user() ? auth()->user()->can('modify', $listing) : false, // pass as props if user can modify the record
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        Gate::authorize('modify', $listing);
        return inertia('Listing/Edit',[
            "listing" => $listing,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        Gate::authorize('modify', $listing);
        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'desc' => ['required'],
            'tags' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'link' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'max:3072', 'mimes:jpeg,jpg,png,webp'],
        ]);

        if ($request->hasFile('image')) {
            if ($listing->image) {
                Storage::disk('public')->delete($listing->image);
            }
            $fields['image'] = Storage::disk('public')->put('images/listing', $request->image);
        } else {
            $fields['image'] = $listing->image;
        }

        $fields['tags'] = implode(',', array_unique(array_filter(array_map('trim', explode(',',  $request->tags)))));

        $listing->update($fields);

        return redirect()->route('dashboard')->with('status', 'Listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        Gate::authorize('modify', $listing);
        // If listing has image, Delete image from storage
       if($listing->image) {
            Storage::disk('public')->delete($listing->image);
       }

       $listing->delete();

       return redirect()->route('dashboard')->with('status', 'Listing deleted successfully!');
    }
}
