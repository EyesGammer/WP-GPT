<?php
$required = array( 'name', 'display', 'placeholder' );
foreach( $required as $loop_required ) {
    if( ! isset( $args[ 'config' ][ $loop_required ] ) ) $args[ 'config' ][ $loop_required ] = '';
}
?>
<div class="kumo-gpt-text-container">
    <label for="<?= $args[ 'config' ][ 'name' ] ?>"><?= $args[ 'config' ][ 'display' ] ?></label>
    <input
        type="text"
        name="<?= $args[ 'config' ][ 'name' ] ?>"
        id="<?= $args[ 'config' ][ 'name' ] ?>"
        placeholder="<?= $args[ 'config' ][ 'placeholder' ] ?>"
    >
</div>
<style>
    .kumo-gpt-text-container {
        position: relative;
        width: 100%;
        height: fit-content;
        display: flex;
        flex-direction: column;
    }
</style>