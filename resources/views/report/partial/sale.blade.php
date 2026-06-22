@foreach($transactions as $transaction)
<tr id="check-tr" data-id="{{ $transaction['id'] }}" style="border:none !important;">
    <td>{{ $transaction['transaction_date'] }}</td>
    <td>{!! $transaction['incoice_no'] !!}</td>
    <td>{{ $transaction['seller'] }}</td>
    <td>{{ $transaction['type'] }}</td>
    <td>{{ $transaction['customer'] }}</td>
    <td>{{ $transaction['total_qty'] }}</td>
    <td>{{ $transaction['grand_total'] }}</td>
    <td>{{ $transaction['paid_amount'] }}</td>
    <td>{{ $transaction['due_amount'] }}</td>
    <td style="text-align:center;">
        @if($transaction['payment_status'] == 'paid')
            <span class="badge bg-success shadow-md dark:group-hover:bg-transparent">{{ $transaction['payment_status'] }}</span>
        @elseif($transaction['payment_status'] == 'patial')
            <span class="badge bg--primary shadow-md dark:group-hover:bg-transparent">{{ $transaction['payment_status'] }}</span>
        @else
            <span class="badge bg-danger shadow-md dark:group-hover:bg-transparent">{{ $transaction['payment_status'] }}</span>
        @endif
    </td>
</tr>
@endforeach