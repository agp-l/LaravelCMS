<section class="pt-0">
	<div class="container">
		<div class="inner-container-small text-center mb-4 mb-sm-5">
			<h2 class="mb-4">
				{{ $columns['content1'] ?? 'Default Content 1' }}
			</h2>
			<p class="mb-4">
				{{ $columns['content2'] ?? 'Default Content 2' }}
			</p>
			<a href="{{ $columns['content3'] ?? '#' }}" class="btn btn-dark mb-0">
				{{ $columns['content3'] ?? 'Odkaz' }}
			</a>
		</div>
	</div>
</section>











<div class="bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="section-heading display-4 mb-3">Discover Features</h1>
            <p class="text-muted lead">Explore our powerful tools designed to enhance your workflow</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card h-100 p-4">
                    <div class="icon-wrapper bg-soft-primary">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                            class="text-primary">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    </div>
                    <h3 class="card-title">Smart Analytics</h3>
                    <p class="card-text text-muted">Get deep insights into your data with our advanced analytics tools.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card h-100 p-4">
                    <div class="icon-wrapper bg-soft-success">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                            class="text-success">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                    </div>
                    <h3 class="card-title">Real-time Monitoring</h3>
                    <p class="card-text text-muted">Monitor your systems in real-time with instant notifications.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card h-100 p-4">
                    <div class="icon-wrapper bg-soft-warning">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                            class="text-warning">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <h3 class="card-title">Advanced Security</h3>
                    <p class="card-text text-muted">Enterprise-grade security to protect your valuable data.</p>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- Feature Cards -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Feature Card 1 -->
            <div class="col-md-4">
                <div class="card feature-card shadow-sm h-100 p-4">
                    <div class="icon-box bg-primary bg-opacity-10 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-lightning-charge text-primary" viewBox="0 0 16 16">
                            <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09z"/>
                        </svg>
                    </div>
                    <h4 class="mb-3">Fast Performance</h4>
                    <p class="text-muted">Optimize your website for lightning-fast loading speeds and seamless user experience.</p>
                </div>
            </div>

            <!-- Feature Card 2 -->
            <div class="col-md-4">
                <div class="card feature-card shadow-sm h-100 p-4">
                    <div class="icon-box bg-success bg-opacity-10 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-shield-check text-success" viewBox="0 0 16 16">
                            <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                        </svg>
                    </div>
                    <h4 class="mb-3">Secure Platform</h4>
                    <p class="text-muted">Enhanced security measures to protect your data and ensure safe transactions.</p>
                </div>
            </div>

            <!-- Feature Card 3 -->
            <div class="col-md-4">
                <div class="card feature-card shadow-sm h-100 p-4">
                    <div class="icon-box bg-warning bg-opacity-10 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear text-warning" viewBox="0 0 16 16">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                        </svg>
                    </div>
                    <h4 class="mb-3">Easy Configuration</h4>
                    <p class="text-muted">Simple and intuitive settings to customize your experience exactly how you want it.</p>
                </div>
            </div>
        </div>
    </div>
</section>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<div class="container py-5">
    <h2 class="text-center mb-4">Our Products</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <!-- Product 1 -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1598618826732-fb2fdf367775?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw1fHxzbWFydHBob25lfGVufDB8MHx8fDE3MjEzMDU4NTZ8MA&ixlib=rb-4.0.3&q=80&w=1080" class="card-img-top" alt="Product 1">
                <div class="card-body">
                    <h5 class="card-title">Product 1</h5>
                    <p class="card-text">A brief description of Product 1 and its features.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0">$19.99</span>
                        <button class="btn btn-outline-primary"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product 2 -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1720048171731-15b3d9d5473f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MXwxfHNlYXJjaHwxfHxzbWFydHBob25lfGVufDB8MHx8fDE3MjEzMDU4NTZ8MA&ixlib=rb-4.0.3&q=80&w=1080" class="card-img-top" alt="Product 2">
                <div class="card-body">
                    <h5 class="card-title">Product 2</h5>
                    <p class="card-text">A brief description of Product 2 and its features.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0">$24.99</span>
                        <button class="btn btn-outline-primary"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product 3 -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1600087626120-062700394a01?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw2fHxzbWFydHBob25lfGVufDB8MHx8fDE3MjEzMDU4NTZ8MA&ixlib=rb-4.0.3&q=80&w=1080" class="card-img-top" alt="Product 3">
                <div class="card-body">
                    <h5 class="card-title">Product 3</h5>
                    <p class="card-text">A brief description of Product 3 and its features.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0">$29.99</span>
                        <button class="btn btn-outline-primary"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product 4 -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1598965402089-897ce52e8355?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw0fHxzbWFydHBob25lfGVufDB8MHx8fDE3MjEzMDU4NTZ8MA&ixlib=rb-4.0.3&q=80&w=1080" class="card-img-top" alt="Product 4">
                <div class="card-body">
                    <h5 class="card-title">Product 4</h5>
                    <p class="card-text">A brief description of Product 4 and its features.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0">$34.99</span>
                        <button class="btn btn-outline-primary"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








