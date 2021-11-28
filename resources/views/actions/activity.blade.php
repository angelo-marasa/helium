@extends('layouts.master')

@section('meta')
<meta http-equiv="refresh" content="300">
@endsection
            
@section('content')
<div class="flex flex-col">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div
        class="
          shadow
          overflow-hidden
          border-b border-gray-200
          sm:rounded-lg
        "
      >
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              </th>
              <th
                scope="col"
                class="
                  px-6
                  py-3
                  text-left text-xs
                  font-medium
                  text-gray-900
                  uppercase
                  tracking-wider
                "
              >
                Hotspot Name
              </th>
              <th
                scope="col"
                class="
                  px-6
                  py-3
                  text-left text-xs
                  font-medium
                  text-gray-900
                  uppercase
                  tracking-wider
                "
              >
                Last Activity
              </th>
              <th
                scope="col"
                class="
                  px-6
                  py-3
                  text-left text-xs
                  font-medium
                  text-gray-900
                  uppercase
                  tracking-wider
                "
              >
                Last 24 Hrs
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($hotspots as $hotspot)
                <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                    {{ $hotspot->hotspot_name }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                    {{ $hotspot->last_active }} Blocks Ago
                    </div>
                </td>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                    {{ $hotspot->day_earnings }} HNT
                    </div>
                </td>
                </tr>
            @endforeach
            <!-- More people... -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection