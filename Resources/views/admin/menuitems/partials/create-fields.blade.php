<div class="form-group">
    <label for="module">Module</label>
    <select class="form-control" name="module" id="module">
        <option value=""></option>
        <?php foreach(Module::enabled() as $module): ?>
            <option value="{{ strtolower($module) }}">{{ $module }}</option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <label for="target">Target</label>
    <select class="form-control" name="target" id="target">
        <option value="_self">Same tab</option>
        <option value="_blank">New tab</option>
    </select>
</div>
