<div class="form-group">
    <label for="page">{{ trans('menu::menu-items.form.page') }}</label>
    <select class="form-control" name="page_id" id="page">
        <option value=""></option>
        <?php foreach($pages as $page): ?>
            <option value="{{ $page->id }}" {{ $menuItem->page_id == $page->id ? 'selected' : '' }}>
                {{ $page->title }}
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <label for="module">{{ trans('menu::menu-items.form.module') }}</label>
    <select class="form-control" name="module" id="module">
        <option value=""></option>
        <?php foreach(Module::enabled() as $module): ?>
            <option value="{{ strtolower($module) }}" {{ $menuItem->module_name == strtolower($module) ? 'selected' : '' }}>{{ $module }}</option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <label for="target">{{ trans('menu::menu-items.form.target') }}</label>
    <select class="form-control" name="target" id="target">
        <option value="_self" {{ $menuItem->target == '_self' ? 'selected' : '' }}>{{ trans('menu::menu-items.form.same tab') }}</option>
        <option value="_blank" {{ $menuItem->target == '_blank' ? 'selected' : '' }}>{{ trans('menu::menu-items.form.new tab') }}</option>
    </select>
</div>
