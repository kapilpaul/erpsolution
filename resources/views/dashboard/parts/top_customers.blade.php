<div class="card">
    <div class="card-header white">
        <h6> Top Customers </h6>
    </div>
    <div class="card-body p-0">
        <div class="lightSlider" data-item="6" data-item-xl="4" data-item-md="2" data-item-sm="1" data-pause="7000" data-pager="false" data-auto="true"
             data-loop="true">
            @foreach($topCustomers as $item)
            <div class="p-5 {{ ($loop->iteration == 1) ? 'bg-primary text-white' : '' }} {{ ($loop->iteration%2 == 1) ? 'light' : '' }}">
                <h5 class="font-weight-normal s-14">{{ $item->name }}</h5>
                <span class="s-48 font-weight-lighter text-primary">675</span>
                <div> Oxygen
                    <span class="text-primary">
                        <i class="icon icon-arrow_downward"></i> 67%</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>