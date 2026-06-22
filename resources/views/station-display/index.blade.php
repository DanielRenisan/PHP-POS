@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold">{{ $station->name }} <span class="text-gray-500 text-base">({{ $station->code }})</span></h1>
            <p class="text-sm text-gray-500">{{ $station->ticket_name }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" class="btn btn-outline-primary" onclick="window.location.reload()">Refresh</button>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="p-10 text-center text-gray-500 border rounded">No active tickets for this station.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($tickets as $ticket)
                <div class="border rounded shadow-sm p-4 bg-white">
                    <div class="flex items-center justify-between border-b pb-2 mb-2">
                        <div>
                            <div class="font-bold">{{ $ticket->ticket_code }} #{{ $ticket->ticket_no ?? $ticket->id }}</div>
                            <div class="text-xs text-gray-500">{{ $ticket->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div>
                            <span class="badge
                                @if($ticket->status === 'printed') bg-success
                                @elseif($ticket->status === 'failed') bg-danger
                                @elseif($ticket->status === 'pending') bg-warning
                                @else bg-secondary
                                @endif
                            ">
                                {{ strtoupper($ticket->status) }}
                            </span>
                        </div>
                    </div>

                    <ul class="divide-y">
                        @foreach($ticket->lines as $line)
                            <li class="py-2 flex items-center justify-between gap-2">
                                <div>
                                    <div class="font-medium">{{ $line->quantity }} × {{ $line->product_name }}</div>
                                    @if($line->variation_name)
                                        <div class="text-xs text-gray-500">{{ $line->variation_name }}</div>
                                    @endif
                                    @if($line->notes)
                                        <div class="text-xs text-amber-600">{{ $line->notes }}</div>
                                    @endif
                                </div>
                                <select
                                    class="form-select text-sm"
                                    onchange="updateLineStatus({{ $line->id }}, this.value)"
                                >
                                    @foreach(\App\Models\StationOrderTicketLine::statuses() as $s)
                                        <option value="{{ $s }}" {{ $line->status === $s ? 'selected' : '' }}>
                                            {{ ucfirst($s) }}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-3 flex justify-end gap-2">
                        @if($ticket->status === 'failed' || $ticket->status === 'printed' || $ticket->status === 'reprinted')
                            @can('station.ticket.reprint')
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="reprint({{ $ticket->id }})">Reprint</button>
                            @endcan
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function updateLineStatus(lineId, status) {
    fetch("{{ url('station-display/lines') }}/" + lineId + "/status", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({status: status})
    }).then(r => r.json()).then(d => {
        if (!d.success) alert("Update failed");
    });
}

function reprint(ticketId) {
    if (!confirm("Reprint this ticket?")) return;
    fetch("{{ url('station-tickets') }}/" + ticketId + "/reprint", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    }).then(r => r.json()).then(d => {
        if (d.success) window.location.reload();
        else alert("Reprint failed");
    });
}
</script>
@endsection
