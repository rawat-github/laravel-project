<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = DB::table('address_book')->orderBy('last_name', 'asc')->get();
        return view('address-book.contact-list', [
            'contacts' => $contacts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('address-book.add-contact');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'email|unique:address_book'
        ]);
        DB::table('address_book')->insert([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'country' => $request->input('country'),
            'city' => $request->input('city'),
            'street' => $request->input('street'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);
        return redirect('/addressbook');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contact = DB::table('address_book')->find($id);
        return view('address-book.edit-contact', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => 'email|unique:address_book,email,'.$id
        ]);
        DB::table('address_book')
            ->where('id', $id)
            ->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'country' => $request->input('country'),
                'city' => $request->input('city'),
                'street' => $request->input('street'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
            ]);
        return redirect('/addressbook');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('address_book')->where('id', '=', $id)->delete();
        return redirect()->back();
    }
}
