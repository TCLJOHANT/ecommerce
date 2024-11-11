<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <h1 class="text-4xl font-bold text-slate-500">Mis Ordenes</h1>
    <div class="flex flex-col bg-white p-5 rounded mt-4 shadow-lg">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Orden</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Estado de
                                    la orden</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Estado del
                                    pago</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Total Orden
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php
                                    $status = '';
                                    $payment_status = '';

                                    $status = match ($order->status) {
                                        'new' => '<span class="bg-blue-500 py-1 px-3 rounded text-white shadow">
                                                    Nuevo
                                                </span>',
                                        'processing' => '<span class="bg-yellow-500 py-1 px-3 rounded text-white shadow">
                                                Prosesando
                                                </span>',
                                        'shipped' => '<span class="bg-green-500 py-1 px-3 rounded text-white shadow">
                                                Pagado
                                                </span>',
                                        'delivered' => '<span class="bg-green-700 py-1 px-3 rounded text-white shadow">
                                                Entregado
                                                </span>',
                                        'canceled' => '<span class="bg-red-500 py-1 px-3 rounded text-white shadow">
                                                    Cancelado
                                                </span>',
                                    };

                                    $payment_status = match ($order->payment_status) {
                                        'paid' => '<span class="bg-green-500 py-1 px-3 rounded text-white shadow">
                                                    Pagado
                                                </span>',
                                        'pending' => '<span class="bg-yellow-500 py-1 px-3 rounded text-white shadow">
                                                    Pendiente
                                                </span>',
                                        'failed' => '<span class="bg-red-500 py-1 px-3 rounded text-white shadow">
                                                    Fallido
                                                </span>',
                                    };
                                @endphp
                                <tr wire:key="{{ $order->id }}"
                                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-900 dark:even:bg-slate-800">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $order->created_at->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {!! $status !!}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {!! $payment_status !!}</td>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ Number::currency($order->grand_total, 'COL') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <a href="/my-orders/{{ $order->id }}" wire:navigate
                                            class="bg-slate-600 text-white py-2 px-4 rounded-md hover:bg-slate-500">Ver
                                            Detalles</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>
