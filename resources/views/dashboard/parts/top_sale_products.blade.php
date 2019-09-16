<div class="card">
    <div class="card-header white">
        <strong> {{ $name }} </strong>
    </div>
    <div class="card-body pt-0">
        <div style="height: 450px">
            <canvas
                    data-chart="bar"
                    data-dataset="[{{$topSalesProducts['topSalesProductQty']}}]"
                    data-labels="{{ $topSalesProducts['topSalesProductNames'] }}"
                    data-dataset-options="[
                        {
                            label:'{{ $name }}',
                            borderColor:  'rgba(68,41,8,0.74)',
                            backgroundColor: ['rgba(3, 169, 244,0.7)', 'rgba(76, 175, 80,0.7)', 'rgba(205, 220, 57,0.7)', 'rgba(0, 150, 136,0.7)', 'rgba(121, 85, 72,0.7)']
                        }
                    ]"
                    data-options="{
                        maintainAspectRatio: false,
                        legend: {
                            display: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                gridLines: {
                                    zeroLineColor: '#eee',
                                    color: '#eee',

                                    borderDash: [5, 5],
                                }
                            }],
                            yAxes: [{
                                display: true,
                                gridLines: {
                                    zeroLineColor: '#eee',
                                    color: '#eee',
                                    borderDash: [5, 5],
                                }
                            }]

                        },
                        elements: {
                            line: {

                                tension: 0.4,
                                borderWidth: 1
                            },
                            point: {
                                radius: 2,
                                hitRadius: 10,
                                hoverRadius: 6,
                                borderWidth: 4
                            }
                        }
                    }">
            </canvas>
        </div>
    </div>
</div>