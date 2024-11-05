<?php
if(
    empty( $args[ 'prompts' ] ) ||
    ! is_array( $args[ 'prompts' ] )
) {
    ?>
    <span><i><?= _e( 'Aucun prompt disponible...', KUMO_GPT_DOMAIN ) ?></i></span>
    <?php
    return;
}
?>
<div class="">
    <label for="gpt-prompt">Prompt :</label>
    <select name="gpt-prompt" id="gpt-prompt" class="regular-text">
        <option value="none" data-target-prompt="none"><?= _e( "Sélectionnez un prompt", KUMO_GPT_DOMAIN ) ?></option>
        <?php
        foreach( $args[ 'prompts' ] as $loop_prompt ) {
            ?>
            <option value="<?= $loop_prompt[ 'slug' ] ?>" data-target-prompt="<?= $loop_prompt[ 'uuid' ] ?>"><?= $loop_prompt[ 'nom' ] ?></option>
            <?php
        }
        ?>
    </select>
</div>
<div id="kumo-gpt-container">
    <div id="kumo-gpt-content">
        <div class="kumo-gpt-config-container" data-prompt-id="none">
            <span style="font-style: italic;"><?= _e( 'Veuillez sélectionner un prompt...', KUMO_GPT_DOMAIN ) ?></span>
        </div>
        <?php
        foreach( $args[ 'prompts' ] as $loop_prompt ) {
            $config = $loop_prompt[ 'config' ];
            ?>
            <div class="kumo-gpt-config-container" data-prompt-id="<?= $loop_prompt[ 'uuid' ] ?>">
                <h2 style="font-size: 19px;font-weight: 600;"><?= $loop_prompt[ 'description' ] ?></h2>
                <?php
                $to_fetch = array();
                foreach( $config as $target => $loop_config ) {
                    $to_fetch[] = $loop_config[ 'name' ];
                    switch( $loop_config[ 'type' ] ) {
                        case 'textarea':
                        case 'text':
                        case 'number':
                        case 'selector':
                            load_template(
                                plugin_dir_path( __FILE__ ) . $loop_config[ 'type' ] . '-input.php',
                                false,
                                array(
                                    'target' => $target,
                                    'config' => $loop_config
                                )
                            );
                            break;
                    }
                }
                ?>
                <button class="button-primary kumo-gpt-button" data-prompt="<?= $loop_prompt[ 'slug' ] ?>" data-fetch="<?= htmlspecialchars( json_encode( $to_fetch ) ) ?>">
                    <img src="<?= plugins_url( 'kumo-gpt' ) . '/assets/logo-1024.svg' ?>" alt="Logo Kumo GPT" class="filter-white">
                    <?= _e( 'Envoyer', KUMO_GPT_DOMAIN ) ?>
                </button>
            </div>
            <?php
        }
        ?>
    </div>
    <div id="kumo-gpt-result">
        <label><?= _e( 'Résultat', KUMO_GPT_DOMAIN ) ?> :</label>
        <p></p>
        <i style="margin-top:1.5rem;margin-bottom:.5rem;display:inline-block;"><?= _e( 'Copier/Coller ou appuyez sur le bouton ci-dessous.', KUMO_GPT_DOMAIN ) ?></i>
        <button class="button-secondary kumo-gpt-copy-button" style="display: none !important;">
            <img src="<?= plugins_url( 'kumo-gpt' ) . '/assets/logo-1024.svg' ?>" alt="Logo Kumo GPT">
            <?= _e( "Insérer dans l'éditeur", KUMO_GPT_DOMAIN ) ?>
        </button>
        <span id="need-to-show" style="display: none !important;font-style: italic;font-size: 15px;color: rgb(209 213 219);"><?= _e( 'UUID Requête', KUMO_GPT_DOMAIN ) ?> : <b id="can-copy-text" style="cursor: pointer;">test</b></span>
    </div>
</div>
<script>
    ( buttons => {
        const result_copy_button = document.querySelector( '#kumo-gpt-result .kumo-gpt-copy-button' );
        const gpt_container = document.querySelector( '#kumo-gpt-container' );
        const gpt_result = document.querySelector( '#kumo-gpt-result p' );
        const can_be_copied = document.querySelector( '#can-copy-text' );
        can_be_copied.addEventListener( 'click', event => {
            if( navigator && navigator.clipboard && navigator.clipboard.writeText ) navigator.clipboard.writeText( can_be_copied.innerText );
        } );
        result_copy_button.addEventListener( 'click', event => {
            event.preventDefault();
            window?.tinymce?.editors[ 0 ]?.insertContent( gpt_result.innerText.replace( /\r?\n/g, '<br />' ), {
                format: 'html'
            } );
        } );
        buttons.forEach( button => {
            button.addEventListener( 'click', event => {
                event.preventDefault();
                const to_fetch = JSON.parse( button.dataset.fetch ).map( item => ( {
                    [ document.querySelector( '#' + item ).name ]: document.querySelector( '#' + item ).value
                } ) ).reduce( ( obj, item ) => {
                    Object.assign( obj, item );
                    return obj;
                }, {} );
                const show_spinner = id => {
                    let new_spinner = document.createElement( 'div' );
                    new_spinner.id = id;
                    let new_spinner_child = document.createElement( 'div' );
                    new_spinner_child.className = "spinner";
                    new_spinner_child.style.opacity = '1';
                    new_spinner_child.style.visibility = 'visible';
                    new_spinner_child.style.visibility = 'visible';
                    new_spinner.appendChild( new_spinner_child );
                    return new_spinner;
                };
                const prompt = button.dataset.prompt;
                gpt_container?.appendChild( show_spinner( 'kumo-gpt-overlay' ) );
                const gpt_overlay = gpt_container?.querySelector( '#kumo-gpt-overlay' );
                window?.wp?.ajax?.post( 'oct_gpt_correction', {
                    content: to_fetch,
                    slug: prompt
                } )
                    .done( response => {
                        gpt_overlay?.remove();
                        gpt_result.innerText = response.content;
                        can_be_copied.innerText = response.stat_uid;
                        result_copy_button.style.display = 'flex';
                        document.querySelector( '#need-to-show' ).style.display = 'inline-block';
                    } )
                    .fail( error => {
                        gpt_overlay?.remove();
                        console.error( error );
                    } );
            } );
        } );
    } )( [...document.querySelectorAll( '.kumo-gpt-button' )] );

    ( prompt_selector => {
        const prompts = [...document.querySelectorAll( '#kumo-gpt-container #kumo-gpt-content .kumo-gpt-config-container' )];
        const changeCallback = () => {
            let selected = [...prompt_selector.querySelectorAll( 'option' )][ prompt_selector.selectedIndex ];
            prompts.filter( item => item.dataset.promptId !== selected.dataset.targetPrompt ).forEach( item => item.style.display = 'none' );
            prompts.filter( item => item.dataset.promptId === selected.dataset.targetPrompt ).forEach( item => item.style.display = 'flex' );
        };
        prompt_selector.addEventListener( 'change', changeCallback );
        changeCallback();
    } )( document.querySelector( '#gpt-prompt' ) );
</script>
<style>
    .kumo-gpt-button {
        margin-top: 1rem !important;
    }
    .kumo-gpt-button,
    .kumo-gpt-copy-button {
        display: flex !important;
        flex-direction: row;
        align-items: center;
        gap: .5rem;
        width: fit-content;
        padding-block: 4px !important;
        font-size: 17px !important;
    }
    .kumo-gpt-button img,
    .kumo-gpt-copy-button img {
        width: 35px;
        height: auto;
        aspect-ratio: 1;
    }
    img.filter-white {
        filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(157deg) brightness(104%) contrast(101%);
    }
    #kumo-gpt-container {
        width: 100%;
        height: fit-content;
        margin-top: 1.5rem;
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(2,minmax(0,1fr));
    }
    #kumo-gpt-container #kumo-gpt-result {
        display: flex;
        flex-direction: column;
    }
    #kumo-gpt-container #kumo-gpt-content {
        width: 100%;
        height: 100%;
        border-right: 1px solid rgb(51 65 85 / .3);
    }
    #kumo-gpt-container #kumo-gpt-content .kumo-gpt-config-container {
        width: 90%;
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: .5em;
    }
</style>