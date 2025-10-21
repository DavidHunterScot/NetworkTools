<?php $page_title_html = '<img src="/assets/images/icons/file.svg"> <b>WHOIS</b> Tool'; ?>
<?php include __DIR__ . DIRECTORY_SEPARATOR . 'header.php'; ?>

    <form class="w3-padding-32" method="post">
        <p>
            <label for="hostname">Hostname</label>
            <input type="text" id="hostname" name="hostname" class="w3-input"<?php if( $params[ 'hostname' ] ) echo ' value="' . $params[ 'hostname' ] . '"'; ?>>
            <span class="w3-text-gray w3-small">example.tld</span>
        </p>

        <p>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[ 'csrf_token' ]; ?>">
            <button type="submit" class="w3-button w3-border w3-border-gray w3-round-large">Submit</button>
        </p>
    </form>

    <?php
    
    if( isset( $params[ 'result' ][ 'type' ] ) && $params[ 'result' ][ 'type' ] == "success" )
    {
        $result = $params[ 'result' ][ 'result' ];
        $result_lines = explode( "\n", $result );
        $special_result_lines = array( "Domain Name", "Creation Date", "Updated Date", "Registry Expiry Date", "Registrar", "Name Server", "DNSSEC" );

        $result_items = array();

        foreach( $result_lines as $result_line )
        {
            $line_parts = explode( ": ", $result_line );

            if( count( $line_parts ) == 2 )
                $result_items[ $line_parts[ 0 ] ][] = $line_parts[ 1 ];
        }

        echo '<div class="w3-row-padding results-summary">';
        foreach( $result_items as $result_item_key => $result_item_value )
        {
            if( in_array( $result_item_key, $special_result_lines ) )
            {
                echo '<div class="w3-half results-summary-item" style="overflow-x: auto;"><b>' . $result_item_key . ':</b>' . ( count( $result_item_value ) > 1 ? '<br>' : ' ' ) . join( "<br>", $result_item_value ) . '</div>';
            }
        }
        echo '</div>';
        ?>

        <pre class="background-alt w3-padding w3-round" style="overflow-x: auto;"><?php echo $result; ?></pre>
    <?php
    }

    ?>

<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>