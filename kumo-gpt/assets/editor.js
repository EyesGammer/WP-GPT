( function() {
    window.tinymce.PluginManager.add( 'kumo_gpt', function( editor, url ) {
        editor.addButton( 'kumo_gpt', {
            title: 'Correction ChatGPT',
            cmd: 'kumo_gpt_correction',
            image: 'https://lafrenchphoto.com/wp-content/uploads/2021/03/LFPR-0008-C-scaled.jpg'
        } );
        editor.addCommand( 'kumo_gpt_correction', function() {
            let selected_text = editor.selection.getContent( {
                format: 'text'
            } );
            if( selected_text.length === 0 ) {
                selected_text = editor.getContent( {
                    format: 'text'
                } );
            }
            let new_spinner = document.createElement( 'div' );
            new_spinner.id = "kumo-gpt-overlay";
            let new_spinner_child = document.createElement( 'div' );
            new_spinner_child.className = "spinner";
            new_spinner_child.style.opacity = '1';
            new_spinner_child.style.visibility = 'visible';
            new_spinner.appendChild( new_spinner_child );
            document.querySelector( '.wp-editor-expand' )?.appendChild( new_spinner );
            window?.wp?.ajax?.post( 'kumo_gpt_correction', {
                content: selected_text
            } )
                .done( response => {
                    document.querySelector( '.wp-editor-expand #kumo-gpt-overlay' )?.remove();
                    editor.insertContent( selected_text.replace( /\r?\n/g, '<br />' ), {
                        format: 'text'
                    } );
                } )
                .fail( error => {
                    document.querySelector( '.wp-editor-expand #kumo-gpt-overlay' )?.remove();
                    console.error( error );
                } );
        } );
    } );
} )();