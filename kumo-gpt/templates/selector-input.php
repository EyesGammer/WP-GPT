<?php
$required = array( 'name', 'display', 'values' );
foreach( $required as $loop_required ) {
    if( ! isset( $args[ 'config' ][ $loop_required ] ) ) {
        $args[ 'config' ][ $loop_required ] = '';
    }
}
?>
<div class="kumo-gpt-selector-container">
    <label for="<?= $args[ 'config' ][ 'name' ] ?>"><?= $args[ 'config' ][ 'display' ] ?></label>
    <select
        name="<?= $args[ 'config' ][ 'name' ] ?>"
        id="<?= $args[ 'config' ][ 'name' ] ?>"
    >
        <?php
        foreach( $args[ 'config' ][ 'values' ] as $loop_text ) {
            ?>
            <option value="<?= $loop_text ?>"><?= $loop_text ?></option>
            <?php
        }
        ?>
    </select>
</div>
<style>
    .kumo-gpt-selector-container {
        position: relative;
        width: 100%;
        height: fit-content;
        display: flex;
        flex-direction: column;
    }
</style>