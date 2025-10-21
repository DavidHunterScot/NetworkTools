<?php $page_title_html = '<img src="/assets/images/icons/info.svg"> <b>rDNS</b> Tool'; ?>
<?php include __DIR__ . DIRECTORY_SEPARATOR . 'header.php'; ?>

    <form class="w3-padding-32" method="post">
        <p>
            <label for="ip_address">IP Address</label>
            <input type="text" id="ip_address" name="ip_address" class="w3-input"<?php if( $params[ 'ip_address' ] ) echo ' value="' . $params[ 'ip_address' ] . '"'; ?>>
            <span class="w3-text-gray w3-small">127.0.0.1</span>
        </p>
        
        <p>
            <label for="nameservers">Name Server IPs</label>
            <input type="text" id="nameservers" name="nameservers" class="w3-input"<?php if( $params[ 'nameservers' ] ) echo ' value="' . join( ' ', $params[ 'nameservers' ] ) . '"'; ?>>
            <span class="w3-text-gray w3-small">Space Separated List</span>
        </p>
        
        <?php if( strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ) echo '<input type="hidden" name="tool" value="' . $params[ 'tool' ] . '">'; ?>

        <p>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="w3-button w3-border w3-border-gray w3-round-large">Submit</button>
        </p>
    </form>

    <?php
    if( isset( $params[ 'result' ][ 'type' ] ) && $params[ 'result' ][ 'type' ] == "success" )
    {
        foreach( (array) $params[ 'result' ][ 'answers' ] as $type => $answer )
        {
        ?>

        <p class="w3-large w3-border-bottom w3-stretch w3-padding section-title dns-type <?php echo strtolower( $type ); ?>"><b><?php echo $type; ?></b></p>

        <?php
            if( count( ( array ) $answer[ 'answer' ] ) > 0 )
            {
    ?>

        <div class="w3-stretch results-container" style="overflow-x: auto; white-space: nowrap;">
            <table class="w3-table w3-striped">
                <tr>
                    <th>IP Address</th>
                    <th>Type</th>
                    <th>Class</th>
                    <th>TTL</th>
                    <th>Hostname</th>
                </tr>

                <?php foreach( ( array ) $answer['answer'] as $record ): ?>

                <tr class="w3-monospace">
                    <td><?php echo $params[ 'ip_address' ]; ?></td>
                    <td><?php echo $record['type']; ?></td>
                    <td><?php echo $record['class']; ?></td>
                    <td><?php echo $params[ 'networkTools' ]->friendlyTTL( $record['ttl'] ); ?></td>
                    <td><?php echo $record['ptrdname']; ?></td>
                </tr>

                <?php endforeach; ?>

            </table>
        </div>

        <div class="w3-panel w3-stretch results-container">
            <p>Answer from nameserver <code class="background-alt w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code>.</p>
        </div>

        <?php
            }
            else
            {
            ?>

        <div class="w3-panel w3-stretch results-container">
            <p>Nameserver <code class="background-alt w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code> has no <code class="background-alt w3-padding-small w3-round-large">PTR</code> records for hostname <code class="background-alt w3-padding-small w3-round-large"><?php echo $params[ 'ip_address' ]; ?></code>.</p>
        </div>

        <?php
            }
        }
    }
    ?>

<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>