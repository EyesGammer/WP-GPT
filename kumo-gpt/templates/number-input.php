<?php
$required = array( 'name', 'display', 'min', 'max', 'default' );
foreach( $required as $loop_required ) {
    if( ! isset( $args[ 'config' ][ $loop_required ] ) ) $args[ 'config' ][ $loop_required ] = '';
}
?>
<div class="kumo-gpt-number-container">
    <label for="<?= $args[ 'config' ][ 'name' ] ?>"><?= $args[ 'config' ][ 'display' ] ?></label>
    <input
        type="number"
        name="<?= $args[ 'config' ][ 'name' ] ?>"
        id="<?= $args[ 'config' ][ 'name' ] ?>"
        min="<?= ! empty( $args[ 'config' ][ 'min' ] ) ? $args[ 'config' ][ 'min' ] : 0 ?>"
        max="<?= ! empty( $args[ 'config' ][ 'max' ] ) ? $args[ 'config' ][ 'max' ] : 10 ?>"
        value="<?= ! empty( $args[ 'config' ][ 'default' ] ) ? $args[ 'config' ][ 'default' ] : 2 ?>"
    >
</div>
<script>
    ( number_input => {
        number_input.addEventListener( 'change', () => {
            let max = number_input.getAttribute( 'max' ) ?? null;
            if( max !== null ) number_input.value = parseInt( number_input.value ) > parseInt( max ) ? 400 : number_input.value;
        } );
    } )( document.querySelector( '#<?= $args[ 'config' ][ 'name' ] ?>' ) );
</script>
<style>
    .kumo-gpt-number-container {
        position: relative;
        width: 100%;
        height: fit-content;
        display: flex;
        flex-direction: column;
    }
</style>