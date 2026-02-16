<x-layouts.address-book-layout>
    <div class="overflow-x-auto shadow-md mt-10">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th class="px-6 py-3">
                    First Name
                </th>
                <th class="px-6 py-3">
                    Last Name
                </th>
                <th class="px-6 py-3">
                    Country
                </th>
                <th class="px-6 py-3">
                    City
                </th>
                <th class="px-6 py-3">
                    Street
                </th>
                <th class="px-6 py-3">
                    E-mail
                </th>
                <th class="px-6 py-3">
                    Phone
                </th>
                <th class="px-6 py-3 text-right">
                    <a href="{{ url('/add-contact') }}" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg px-6 py-3 inline-block">Add contact</a>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($contacts as $contact)
                <tr class="bg-white border-b hover:bg-blue-50">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $contact->first_name }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $contact->last_name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $contact->country }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $contact->city }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $contact->street }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $contact->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $contact->phone }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ url('/edit-contact', [$contact->id]) }}" class="font-medium text-blue-600 hover:underline">Edit</a> / <a href="{{ url('/delete-contact', [$contact->id]) }}" onclick="return confirm('Do you want to delete?')" class="font-medium text-red-600 hover:underline">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.address-book-layout>
