@extends('paagez::layouts.admin-navbar-only')

@push('meta')
<title>{{config('paagez.app_name')}}</title>
@endpush
@section('contents')
<div class="card p-3 shadow card-min-height">
	<h3>Oops</h3>
	<p>Please add <code class="text-danger">config</code> and <code class="text-danger">update_config</code> function on <strong><code>\{{$namespace}}\Module</code></strong></p>
	<p>Here is the example:</p>
<pre>
	<code class="text-danger">
		public function config()
		{
			$config = ModuleConfig::get($this->name);
			return view($this->name.'::config',compact('config'));
		}

		public function update_config()
		{
			$request = request()->except('_token');
			$validator = Validator::make($request, [
				//'name' => 'required|string|max:255',
			]);
			if ($validator->fails()) {
				return redirect()
				->back()
				->withErrors($validator)
				->withInput();
			}
			ModuleConfig::update($this->name,$request);
			return redirect()->back()->with(['success' => __('paagez.config_updated',['name'=>$this->title])]);
		}
	</code>
</pre>
</div>
@endsection