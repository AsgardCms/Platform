<div class='form-group{{ $errors->has('tags') ? ' has-error' : '' }}'>
    {!! Form::label('tags', $name) !!}
    <select name="tags[]" id="tags" class="input-tags" multiple>
        <?php foreach ($availableTags as $tag): ?>
        <option value="{{ $tag->slug }}" {{ in_array($tag->slug, $tags) ? ' selected' : null }}>{{ $tag->name }}</option>
        <?php endforeach; ?>
    </select>
    {!! $errors->first('tags', '<span class="help-block">:message</span>') !!}
</div>
<script>
    $( document ).ready(function() {
        $('.input-tags').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
    });
</script>
