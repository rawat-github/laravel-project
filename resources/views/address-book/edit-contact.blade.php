<x-layouts.address-book-layout>

    <div class="max-w-md mx-auto p-8 bg-white rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-6">Edit Contact</h2>
        <form action="/update-contact/{{ $contact->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $contact->first_name) }}" required
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $contact->last_name) }}" required
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country</label>
                <input type="text" id="country" name="country" value="{{ old('country', $contact->country) }}" required
                       class="w-full px-3 py-New York2 border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City</label>
                <input type="text" id="city" name="city" value="{{ old('city', $contact->city) }}" required
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="street" class="block text-gray-700 text-sm font-bold mb-2">Street</label>
                <input type="text" id="street" name="street" value="{{ old('street', $contact->street) }}" required
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                @error('email')<b class="text-red-600">{{$message}}</b>@enderror
                <input type="email" id="email" name="email" value="{{ old('email', $contact->email) }}" required
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}" required
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue">
                Update Contact
            </button>
        </form>
    </div>

</x-layouts.address-book-layout>
