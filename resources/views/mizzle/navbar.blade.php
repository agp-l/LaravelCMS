<header class="header-sticky header-absolute" data-bs-theme="dark">

	<nav class="navbar navbar-expand-xl">
		<div class="container">

			<!-- Logo -->
			<a class="navbar-brand me-0" href="{{ url('/') }}">
				<h3 class="mb-0">
					<span>dobrodruzi</span>
				</h3>
			</a>

			<!-- Burger button (přesně podle původní šablony) -->
			<button class="navbar-toggler ms-sm-3 p-2" type="button" data-bs-toggle="collapse"
				data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-animation">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>

			<!-- Menu (přesně jako ve statické šabloně) -->


			<div class="navbar-collapse collapse" id="navbarCollapse">
				<ul class="navbar-nav navbar-nav-scroll dropdown-hover">
					@foreach ($menuTree as $item)
						@if (count($item->children))
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="{{ getMenuUrl($item) }}" data-bs-toggle="dropdown"
									data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
									{{ $item->label }}
								</a>
								<ul class="dropdown-menu">
									@foreach ($item->children as $child)
										<li><a class="dropdown-item" href="{{ getMenuUrl($child) }}">{{ $child->label }}</a></li>
									@endforeach
								</ul>
							</li>
						@else
							<li class="nav-item">
								<a class="nav-link" href="{{ getMenuUrl($item) }}">{{ $item->label }}</a>
							</li>
						@endif
					@endforeach


					@include('mizzle.language-switch')

					@auth
						<!-- Admin -->
						<li class="nav-item dropdown dropdown-animation">
							<button class="btn btn-link mb-0 px-2" id="admin" data-bs-toggle="dropdown">
								<i class="fa-regular fad fa-bars"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="admin">

								@if (session('tinymce_disabled'))
									<li><a href="{{ route('toggle.tinymce') }}" class="dropdown-item"><i
												class="fad fa-toggle-off"></i>
											Zapnout editor</a></li>
								@else
									<li><a href="{{ route('toggle.tinymce') }}" class="dropdown-item"><i
												class="fad fa-toggle-on"></i>
											Vypnout editor</a></li>
								@endif
								<li><a class="dropdown-item" href="{{ route('article.index') }}"><i
											class="fad fa-newspaper"></i>Články</a></li>
								<li><a class="dropdown-item" href="{{ route('page.index') }}"><i
											class="fad fa-file-alt"></i> Stránky</a></li>
								<li><a class="dropdown-item" href="{{ route('menu.index') }}"><i class="fad fa-bars"></i>
										Menu</a></li>
								<li><a class="dropdown-item" href="{{ route('article.create') }}"><i
											class="fad fa-plus-circle"></i> Nový článek</a></li>
								<li><a class="dropdown-item" href="{{ route('page.create') }}"><i
											class="fad fa-plus-square"></i> Nová stránka</a></li>
								<li><a class="dropdown-item" href="{{ route('profile.show') }}"><i
											class="fad fa-user-cog"></i> Profil</a></li>
								<li><a class="dropdown-item" href="{{ route('images.index') }}"><i
											class="fa-regular fa-image"></i> Obrázky</a></li>
								<li><a class="dropdown-item" href="{{ route('admin.register') }}"><i
											class="fad fa-user-plus"></i> Nový uživatel</a></li>
								<li><a class="dropdown-item" href="{{ route('admin.layout-overrides.index') }}"><i
											class="fad fa-user-plus"></i>Téma</a></li>

								<li class="dropdown-item">
									<form method="POST" action="{{ route('logout') }}">
										@csrf
										<button class="nav-link btn btn-link" type="submit"><i
												class="fad fa-sign-out-alt"></i> Odhlásit</button>
									</form>
								</li>
								<!--  <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fad fa-sign-in-alt"></i> Přihlásit</a></li>-->


							</ul>
						</li>
						
					@endauth
					<!-- 
					<li class="nav-item d-none d-sm-block">
						<a href="{{ route('login') }}" class="btn btn-sm btn-light mb-0">
							<i class="bi bi-person-circle me-1"></i>Přihlášení
						</a>
					</li>

				
					<li class="nav-item d-none d-sm-block ms-2">
						@auth
							<a href="{{ route('admin.register') }}" class="btn btn-sm btn-primary mb-0">Registrace</a>
						@endauth
					-->
					</li>
				</ul>
			</div>
		</div>
	</nav>

</header>