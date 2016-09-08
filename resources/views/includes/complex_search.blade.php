<div class="filter advanced-filter">
			<div class="wrapper">
				<div class="filter-text">
					<h4>Найти дом вашей мечты</h4>
					<p>Хотите узнать больше о наших проектах? Высотные, мода резиденции, фирменные квартиры, розничные или коммерческих разработок - от плана собственности и готовые к въезду</p>
				</div>
				<form action="{{ action('MainController@complex_search') }}" method="get" id="filter-form">
					<div class="row1">
						<div class="input-wrapper"> 
							<input type="text" name="search" placeholder="Введите ключевое слово">
						</div>
						<div class="select-wrapper">
							<select class="drop-list" name="country" id="country">
								<option value="" selected>Выберите страну</option>
								@foreach ($countries as $country)
									<option value="{{ $country->id }}"
										>{{ $country->name }}</option>
								@endforeach	
							</select>
							<div class="select-arrow"></div>
						</div>
						<div class="select-wrapper">
							<select class="drop-list" name="city" id="city">
							
							</select>
							<div class="select-arrow"></div>
						</div>
					</div>
					<div class="row2">
						<div class="select-wrapper"> 
							<select class="drop-list" name="region" id="region">
							
							</select>
							<div class="select-arrow"></div>
						</div>
						<div class="select-wrapper"> 
							<select class="drop-list" name="status">
								<option disabled selected>Выберите статус</option>
								<option value="1" {{ 1 == Request::input('status') ? 'selected' : '' }}>Завершено</option>
								<option value="2" {{ 2 == Request::input('status') ? 'selected' : '' }}>В процессе</option>
							</select>
							<div class="select-arrow"></div>
						</div>
						<button class="readmore">Поиск</button>
					</div>
				</form>
			</div>
		</div>