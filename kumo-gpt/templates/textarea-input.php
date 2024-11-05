<?php
$required = array( 'name', 'placeholder', 'display' );
foreach( $required as $loop_required ) {
    if( ! isset( $args[ 'config' ][ $loop_required ] ) ) $args[ 'config' ][ $loop_required ] = '';
}
?>
<div class="kumo-gpt-textarea-container">
    <label for="<?= $args[ 'config' ][ 'name' ] ?>"><?= $args[ 'config' ][ 'display' ] ?></label>
    <textarea class="regular-text" name="<?= $args[ 'config' ][ 'name' ] ?>" id="<?= $args[ 'config' ][ 'name' ] ?>" style="width:100%;" rows="10"><?= $args[ 'config' ][ 'placeholder' ] ?></textarea>
</div>
<style>
    .kumo-gpt-textarea-container {
        position: relative;
        width: 100%;
        height: fit-content;
        display: flex;
        flex-direction: column;
    }
</style>