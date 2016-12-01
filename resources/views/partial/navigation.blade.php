<div class="navbar-header header-top">
	<div class="header-top-left-area col-sm-8">
		<ul>
			<li id="ew_social-4" class="widget-container widget_social">						
				<div class="social-icons">
					<ul>
						<li class="icon-facebook"><a href="http://www.facebook.com/#" target="_blank" title="Become our fan"><i class="fa fa-facebook"></i></a></li>				
						
						<li class="icon-twitter"><a href="http://twitter.com/#" target="_blank" title="Follow us"><i class="fa fa-twitter"></i></a></li>
						
						<li class="icon-google"><a href="https://plus.google.com/u/0/#" target="_blank" title="Get updates"><i class="fa fa-google-plus"></i></a></li>
						
						<li class="icon-pin"><a href="http://www.pinterest.com/#" target="_blank" title="See Us"><i class="fa fa-pinterest"></i></a></li>
						
						<li class="icon-instagram"><a href="http://instagram.com/#" target="_blank" title="Follow us"><i class="fa fa-instagram"></i></a></li>
						
						
						<li class="icon-linkedin"><a href="https://www.linkedin.com/pub/#" target="_blank" title="See us"><i class="fa fa-linkedin"></i></a></li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
	<div class="header-top-right-area col-sm-4">
		<ul>
			@include('user.partial.menu_top')
				
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					<span class="glyphicon glyphicon-heart"></span>
				</a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="{{ route('orders.show_wish_list') }}">{{ trans('store.wish_list') }}</a></li>
					<li><a href="{{ route('orders.show_list_directory') }}">{{ trans('store.your_wish_lists') }}</a></li>
				</ul>
			</li>

			@if(Auth::user())
				<li class="dropdown " id="push-notices" ng-controller="PushNoticesController"  ng-click="check()" ng-focus="check()">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						<span class="badge badge-notifications ng-hide" ng-cloak  ng-show="push">[[push]]</span>
						<span class="fui fui-chat"></span>{{ trans('globals.notices') }}
						<span class="visible-xs-inline">
							<span class="caret"></span>
						</span>
					</a>
					<ul class="dropdown-menu notices" role="menu" ng-if="notices.length">
						<li ng-repeat="notice in notices" class="[[notice.status]]">
							<a href="[[getLink(notice)]]" ng-click="check(notice)">[[getDesc(notice)]]</a>
						</li>
						<li>{!! link_to('user/notices/list', trans('globals.all')) !!}</li>
					</ul>
				</li>

				@if (config('app.offering_user_points'))
				<li>
					<a href="{{ route('paypal.buy_points') }}" ng-controller = "PushUsersPoints" ng-init = "pusher()">
						<span class="badge badge-points ng-hide" ng-cloak ng-show = "points" >[[ points | thousandSuffix ]]</span>
						<span class="fui fui-radio-unchecked"></span>{{ trans('store.price') }}
					</a>
				</li>
				@endif
			@endif

			@if (config('app.offering_free_products'))
				<li>
					<a href="{{ route('freeproducts.search') }}">
						<span class="fui fui-star-2"></span>{{ trans('globals.products') }}
						<span class="badge badge-freeproducts">{{ trans('globals.free') }}</span>
					</a>
				</li>
			@endif
		</ul>
	</div>
</div>
<nav ng-controller="CategoriesController" class="cat-controller-nav">
{!! Form::model(Request::all(),['url'=> action('ProductsController@index'), 'method'=>'GET', 'id'=>'searchForm']) !!}
<div class="input-group search-nav">
	<span class="input-group-btn categories-search">
		<button  type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<span ng-bind="catSelected.name || '{{ isset($categories_menu[Request::get('category')]['name']) ? $categories_menu[Request::get('category')]['name'] : trans('store.all_categories') }}'">
				{{ isset($categories_menu[Request::get('category')]['name']) ? $categories_menu[Request::get('category')]['name'] : trans('store.all_categories') }}
				</span> <span class="caret">
			</span>
		</button>
		<ul class="dropdown-menu" role="menu">
			@foreach($categories_menu as $categorie_menu)
				<li >
					<a href="javascript:void(0)"
					   ng-click="setCategorie({{ $categorie_menu['id'] }},'{{ $categorie_menu['name'] }}')" >
						{{ $categorie_menu['name'] }}
					</a>
				</li>
			@endforeach

		</ul>
	</span>
	<input type="hidden" name="category" value="[[refine() || '{{Request::get('category')}}']]"/>

	@include('partial.search_box',['angularController' => 'AutoCompleteCtrl', 'idSearch'=>'search'])

	<span class="input-group-btn">
		<button class="btn btn-default glyphicon glyphicon-search" type="submit"></button>
	</span>
	<div id="navbar" class="navbar-collapse collapse">

	<ul class="nav navbar-nav">
		<li class="dropdown">
			<a href="#cart" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				@if(Auth::user()&&Auth::user()->getCartCount())
				<span class="badge badge-cart">{{ Auth::user()->getCartCount() }} </span>
				@elseif(!Auth::user() && is_array(Session::get('user.cart_content')) && array_sum(Session::get('user.cart_content')))
				<span class="badge badge-cart">{{ array_sum(Session::get('user.cart_content')) }} </span>
				@endif

				<span class="glyphicon glyphicon-shopping-cart"></span>

			</a>

            @if(Auth::user() && Auth::user()->getCartCount() > 0)
                <ul class="dropdown-menu cart" role="menu">
                    @foreach(Auth::user()->getCartContent() as $orderDetail)
                        <li>
                            <a href="{{ route('products.show',[$orderDetail->product->id]) }}" >

                                    <img src="{{ $orderDetail->product->FirstImage }}" alt="{{ $orderDetail->product->name }}" width="32" height="32" style="float: left; margin-right: 2px"/>
                                    {{ $orderDetail->product->name }}
                                     - {{ trans('store.quantity') }}: {{ $orderDetail->quantity }}

                            </a>
                        </li>
                    @endforeach
                    <li><a class="btn btn-default" href="{{ route('orders.show_cart') }}" role="button">{{ trans('store.view_cart') }}</a></li>
                </ul>
            @elseif(!Auth::user() && is_array(Session::get('user.cart_content')))
                <ul class="dropdown-menu cart" role="menu">
                @foreach(Session::get('user.cart_content') as $product_id => $quantity)
                @if($product=\App\Product::find($product_id))
                    <li>
                        <a href="{{ route('products.show',[$product_id]) }}" >

                                <img src="{{ $product->first_image }}" width="32" height="32" style="float: left; margin-right: 2px"/>
                                {{ $product->name }}
                                 - {{ trans('store.quantity') }}: {{ $quantity }}

                        </a>
                    </li>
                @endif
                @endforeach
                <li><a class="btn btn-default" href="{{ route('orders.show_cart') }}" role="button">{{ trans('store.view_cart') }}</a></li>
                </ul>
            @endif
		</li>
	</ul>
</div>

</div>
{!! Form::close() !!}
</nav>
