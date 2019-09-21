<aside class="main-sidebar fixed shadow2 bg-dark no-b theme-dark p-3" data-toggle='offcanvas'>
    <section class="sidebar">
        <div class="w-80px mt-3 mb-3 ml-3">
            <img src="{{ asset('assets/img/basic/logo.png') }}" alt="">
        </div>
        <div class="relative">
            <a class="btn-fab btn-fab-sm absolute fab-right-bottom fab-top btn-primary shadow1 sett">
                <i class="icon icon-cogs"></i>
            </a>
            <div class="user-panel p-3 light mb-2">
                <div>
                    <div class="float-left image">
                        <img class="user_avatar" src="{{ asset('assets/img/dummy/u2.png') }}" alt="User Image">
                    </div>
                    <div class="float-left info">
                        <h6 class="font-weight-light mt-2 mb-1">Alexander Pierce</h6>
                        <a href="#"><i class="icon-circle text-primary blink"></i> Online</a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="" id="userSettingsCollapsed" style="display: none;">
                    <div class="list-group mt-3 shadow">
                        <a href="index.html" class="list-group-item list-group-item-action ">
                            <i class="mr-2 icon-umbrella text-blue"></i>Profile
                        </a>
                        <a href="#" class="list-group-item list-group-item-action"><i
                                    class="mr-2 icon-cogs text-yellow"></i>Settings</a>
                        <a href="#" class="list-group-item list-group-item-action"><i
                                    class="mr-2 icon-security text-purple"></i>Change Password</a>
                    </div>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header"><strong>MAIN NAVIGATION</strong></li>

            <li class="treeview {{ areActiveRoutes(['invoice.create', 'invoice.index']) }}">
                <a href="#">
                    <i class="icon icon-invoice-1 light-green-text s-18"></i> <span>Invoice</span>
                    <i class="icon icon-angle-left s-18 pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ isActiveRoute('invoice.create') }}">
                        <a href="{{ route('invoice.create') }}">
                            <i class="icon icon-folder5"></i>
                            Add
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('invoice.index') }}">
                        <a href="{{ route('invoice.index') }}">
                            <i class="icon icon-folder5"></i>
                            View
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview {{ areActiveRoutes(['transaction.create']) }}">
                <a href="#">
                    <i class="icon icon-paypal light-green-text s-18"></i> <span>Transaction</span>
                    <i class="icon icon-angle-left s-18 pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ isActiveRoute('transaction.create') }}">
                        <a href="{{ route('transaction.create', 'payment') }}">
                            <i class="icon icon-folder5"></i>
                            Payment
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('transaction.create') }}">
                        <a href="{{ route('transaction.create', 'receipt') }}">
                            <i class="icon icon-folder5"></i>
                            Receipt
                        </a>
                    </li>

                    <li class="{{ isActiveRoute('transaction.dailySummary') }}">
                        <a href="{{ route('transaction.dailySummary') }}">
                            <i class="icon icon-circle-o"></i>
                            Daily Reports
                        </a>
                    </li>
                </ul>
            </li>

            {{--<li class="treeview {{ areActiveRoutes(['category.create', 'category.index']) }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="icon icon-sailing-boat-water purple-text s-18"></i> <span>Category</span>--}}
                    {{--<i class="icon icon-angle-left s-18 pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ isActiveRoute('category.create') }}">--}}
                        {{--<a href="{{ route('category.create') }}">--}}
                            {{--<i class="icon icon-folder5"></i>--}}
                            {{--Add--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li class="{{ isActiveRoute('category.index') }}">--}}
                        {{--<a href="{{ route('category.index') }}">--}}
                            {{--<i class="icon icon-folder5"></i>--}}
                            {{--View--}}
                        {{--</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            {{--<li class="treeview no-b {{ areActiveRoutes(['suppliers.create', 'suppliers.index']) }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="icon icon-package light-green-text s-18"></i>--}}
                    {{--<span>Suppliers</span>--}}
                    {{--<i class="icon icon-angle-left s-18 pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ isActiveRoute('suppliers.create') }}">--}}
                        {{--<a href="{{ route('suppliers.create') }}">--}}
                            {{--<i class="icon icon-add"></i>--}}
                            {{--Add--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li class="{{ isActiveRoute('suppliers.index') }}">--}}
                        {{--<a href="{{ route('suppliers.index') }}">--}}
                            {{--<i class="icon icon-circle-o"></i>--}}
                            {{--View--}}
                        {{--</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            <li class="treeview no-b {{ areActiveRoutes(['customer.create', 'customer.index']) }}">
                <a href="#">
                    <i class="icon icon-user-circle-o light-green-text s-18"></i>
                    <span>Customers</span>
                    <i class="icon icon-angle-left s-18 pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ isActiveRoute('customer.create') }}">
                        <a href="{{ route('customer.create') }}">
                            <i class="icon icon-add"></i>
                            Add
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('customer.index') }}">
                        <a href="{{ route('customer.index') }}">
                            <i class="icon icon-circle-o"></i>
                            View
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview {{ areActiveRoutes(['products.create', 'products.index']) }}">
                <a href="#">
                    <i class="icon icon icon-shopping-bag blue-text s-18"></i>
                    <span>Products</span>
                    <span class="badge r-3 badge-primary pull-right">{{ $totalProducts }}</span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ isActiveRoute('products.create') }}">
                        <a href="{{ route('products.create') }}">
                            <i class="icon icon-add"></i>
                            Add
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('products.index') }}">
                        <a href="{{ route('products.index') }}">
                            <i class="icon icon-circle-o"></i>
                            View
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview {{ areActiveRoutes(['purchase.create', 'purchase.index']) }}">
                <a href="#">
                    <i class="icon icon icon-shopping-cart2 blue-text s-18"></i>
                    <span>Purchase</span>
                    <i class="icon icon-angle-left s-18 pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ isActiveRoute('purchase.create') }}">
                        <a href="{{ route('purchase.create') }}">
                            <i class="icon icon-add"></i>
                            Add
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('purchase.index') }}">
                        <a href="{{ route('purchase.index') }}">
                            <i class="icon icon-circle-o"></i>
                            View
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview {{ areActiveRoutes(['reports.stock', 'reports.instock', 'reports.outofstock']) }}">
                <a href="#">
                    <i class="icon icon icon-storage green-text s-18"></i>
                    <span>Stock</span>
                    <i class="icon icon-angle-left s-18 pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ isActiveRoute('reports.stock') }}">
                        <a href="{{ route('reports.stock') }}">
                            <i class="icon icon-circle-o"></i>
                            Stock Report
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('reports.instock') }}">
                        <a href="{{ route('reports.instock') }}">
                            <i class="icon icon-circle-o"></i>
                            In Stock
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('reports.outofstock') }}">
                        <a href="{{ route('reports.outofstock') }}">
                            <i class="icon icon-circle-o"></i>
                            Out Of Stock
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview {{ areActiveRoutes(['accounts.create', 'accounts.index']) }}">
                <a href="#">
                    <i class="icon icon-account_balance_wallet purple-text s-18"></i> <span>Accounts</span>
                    <i class="icon icon-angle-left s-18 pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ isActiveRoute('accounts.create') }}">
                        <a href="{{ route('accounts.create') }}">
                            <i class="icon icon-folder5"></i>
                            Add
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('accounts.index') }}">
                        <a href="{{ route('accounts.index') }}">
                            <i class="icon icon-folder5"></i>
                            View
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview no-b {{ areActiveRoutes(['banks.create', 'banks.index', 'banks.transaction.create', 'banks.transaction.index']) }}">
                <a href="#">
                    <i class="icon icon-bank light-green-text s-18"></i>
                    <span>Banks</span>
                    <i class="icon icon-angle-left s-18 pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ isActiveRoute('banks.create') }}">
                        <a href="{{ route('banks.create') }}">
                            <i class="icon icon-add"></i>
                            Add
                        </a>
                    </li>
                    <li class="{{ isActiveRoute('banks.index') }}">
                        <a href="{{ route('banks.index') }}">
                            <i class="icon icon-circle-o"></i>
                            View
                        </a>
                    </li>
                    <li class="treeview no-b {{ areActiveRoutes(['banks.transaction.create', 'banks.transaction.index']) }}">
                        <a href="#">
                            <i class="icon icon-fingerprint text-green"></i>
                            Bank Transactions
                            <i class="icon icon-angle-left s-18 pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ isActiveRoute('banks.transaction.create') }}">
                                <a href="{{ route('banks.transaction.create') }}">
                                    <i class="icon icon-document"></i>
                                    Add
                                </a>
                            </li>
                            <li class="{{ isActiveRoute('banks.transaction.index') }}">
                                <a href="{{ route('banks.transaction.index') }}">
                                    <i class="icon icon-document"></i>
                                    View
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="icon icon-account_box light-green-text s-18"></i>Users<i
                            class="icon icon-angle-left s-18 pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="panel-page-users.html"><i class="icon icon-circle-o"></i>All Users</a>
                    </li>
                    <li><a href="panel-page-users-create.html"><i class="icon icon-add"></i>Add User</a>
                    </li>
                    <li><a href="panel-page-profile.html"><i class="icon icon-user"></i>User Profile </a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>
<!--Sidebar End-->
