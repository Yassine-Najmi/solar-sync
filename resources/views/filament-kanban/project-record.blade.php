<div class="p-4 bg-white rounded-lg shadow-sm">
    <div class="font-medium">{{ $record->client->name }}</div>
    <div class="text-sm text-gray-500">Start: {{ $record->start_date->format('M d, Y') }}</div>
    <div class="mt-2 text-sm font-medium">${{ number_format($record->project_value, 2) }}</div>
</div>
