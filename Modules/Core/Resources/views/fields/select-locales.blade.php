<div class="form-group">
    <label for="{{ $settingName }}">{{ trans($moduleInfo['description']) }}</label>
    <select multiple class="locales" name="{{ $settingName }}[]" id="{{ $settingName }}">
        @foreach ($locales as $id => $locale)
        <option value="{{ $id }}" {{ isset($dbSettings[$settingName]) && isset(array_flip(json_decode($dbSettings[$settingName]->plainValue))[$id]) ? 'selected' : '' }}>
            {{ array_get($locale, 'name') }}
        </option>
        @endforeach
    </select>
</div>
<script>
    $( document ).ready(function() {
        $('.locales').selectize({
            delimiter: ',',
            plugins: ['remove_button']
        });
    });
</script>
