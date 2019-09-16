<div class="white">
    <div class="card">
        <div class="card-header bg-primary text-white b-b-light">
            <div class="row justify-content-end">
                <div class="col">
                    <ul id="myTab4" role="tablist"
                        class="nav nav-tabs card-header-tabs nav-material nav-material-white float-right">
                        <li class="nav-item">
                            <a class="nav-link active show" id="tab1" data-toggle="tab" href="#v-pills-tab1"
                               role="tab" aria-controls="tab1" aria-expanded="true"
                               aria-selected="true">Today</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab2" data-toggle="tab" href="#v-pills-tab2" role="tab"
                               aria-controls="tab2" aria-selected="false">Yesterday</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body no-p">
            <div class="tab-content">
                <div class="tab-pane animated fadeIn show active" id="v-pills-tab1" role="tabpanel"
                     aria-labelledby="v-pills-tab1">
                    <div class="bg-primary text-white lighten-2">
                        <div class="pt-5 pb-2 pl-5 pr-5">
                            <h5 class="font-weight-normal s-14">Today's Income</h5>
                            <span class="s-48 font-weight-lighter text-primary">
                                    <small>$</small>960</span>
                            <div class="float-right">
                                <span class="icon icon-money-bag s-48"></span>
                            </div>
                        </div>
                        <canvas width="378" height="94" data-chart="spark" data-chart-type="line"
                                data-dataset="[[28,530,200,430]]" data-labels="['a','b','c','d']"
                                data-dataset-options="[
                                    { borderColor:  'rgba(54, 162, 235, 1)', backgroundColor: 'rgba(54, 162, 235,1)'},
                                    ]">
                        </canvas>
                    </div>
                    <div class="b-b">
                        @include('dashboard.parts.invoiceList')
                    </div>
                </div>
                <div class="tab-pane animated fadeIn" id="v-pills-tab2" role="tabpanel"
                     aria-labelledby="v-pills-tab2">
                    <div class="bg-primary text-white lighten-2">
                        <div class="pt-5 pb-2 pl-5 pr-5">
                            <h5 class="font-weight-normal s-14">Yesterday's Income</h5>
                            <span class="s-48 font-weight-lighter text-primary">
                                    <small>$</small>1100</span>
                            <div class="float-right">
                                <span class="icon icon-money-bag s-48"></span>
                            </div>
                        </div>
                        <canvas width="378" height="94" data-chart="spark" data-chart-type="line"
                                data-dataset="[[620,20,700,50]]" data-labels="['a','b','c','d']"
                                data-dataset-options="[
                                    { borderColor:  'rgba(54, 162, 235, 1)', backgroundColor: 'rgba(54, 162, 235,1)'},
                                    ]">
                        </canvas>
                    </div>
                    @include('dashboard.parts.invoiceList')
                </div>
            </div>
        </div>
    </div>
</div>
