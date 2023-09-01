<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . 'NetworkTools.php';

$endpoint = ( isset( $_REQUEST['tool'] ) ? $_REQUEST['tool'] : '' ) . '/' . ( isset( $_REQUEST['hostname'] ) ? $_REQUEST['hostname'] : '' ) . '/' . ( isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : 'A' ) . '/' . ( isset( $_REQUEST['nameservers'] ) ? $_REQUEST['nameservers'] : join( ' ', NetworkTools::DEFAULT_NAMESERVERS ) );

include_once __DIR__ . DIRECTORY_SEPARATOR . 'api.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Network Tools by David Hunter</title>
        
        <link rel="stylesheet" type="text/css" href="https://staticly.cc/w3css/4.15/w3.css">
        <link rel="stylesheet" type="text/css" href="https://staticly.cc/webfonts/poppins/poppins.css">

        <style type="text/css">
            h1, h2, h3, h4, h5, h6, p, th { font-family: "Poppins", sans-serif; }
        </style>
    </head>
    
    <body>
        <header>
            <div class="w3-auto w3-padding">
                <h1><b>Network Tools</b></h1>
                <p>by David Hunter</p>
            </div>
        </header>

        <nav class="w3-bar w3-light-gray">
            <div class="w3-auto">
                <a class="w3-bar-item w3-button w3-bottombar w3-hover-none<?php if( $tool == '' ) echo ' w3-border-gray'; else echo ' w3-border-light-gray'; ?>" href="<?php echo strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ? '/' . basename( __FILE__ ) : '/'; ?>">Home</a>
                <a class="w3-bar-item w3-button w3-bottombar w3-hover-none<?php if( $tool == 'dns' ) echo ' w3-border-gray'; else echo ' w3-border-light-gray'; ?>" href="<?php echo strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ? '/' . basename( __FILE__ ) . '?tool=dns' : '/dns'; ?>">DNS</a>
                <a class="w3-bar-item w3-button w3-bottombar w3-hover-none<?php if( $tool == 'whois' ) echo ' w3-border-gray'; else echo ' w3-border-light-gray'; ?>" href="<?php echo strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ? '/' . basename( __FILE__ ) . '?tool=whois' : '/whois'; ?>">WHOIS</a>
            </div>
        </nav>
        
        <main>
            <div class="w3-auto w3-padding">

<?php

if( $tool == "" )
{
    echo '<p>' . $api_result['message'] . '</p>';
}
elseif( $tool == "dns" )
{
?>

                <h2><b>DNS</b> Tool</h2>

                <form class="w3-padding-32">
                    <p>
                        <label for="hostname">Hostname</label>
                        <input type="text" id="hostname" name="hostname" class="w3-input"<?php if( $hostname ) echo ' value="' . $hostname . '"'; ?>>
                        <span class="w3-text-gray w3-small">example.tld</span>
                    </p>
                    
                    <p>
                        <label for="type">Type</label>
                        <select class="w3-padding-small w3-select" name="type" id="type">
                            <option value="ALL">ALL</option>
                            <?php foreach( $networkTools->getValidTypes() as $validType ): ?>
                                <option value="<?php echo $validType; ?>"<?php if( $validType == $type ) echo ' selected'; ?>><?php echo $validType; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="w3-text-gray w3-small">The DNS record type you wish to query for. Selecting ALL will attempt to retrieve all supported types.</span>
                    </p>
                    
                    <p>
                        <label for="nameservers">Name Server IPs</label>
                        <input type="text" id="nameservers" name="nameservers" class="w3-input"<?php if( $nameservers ) echo ' value="' . join( ' ', $nameservers ) . '"'; ?>>
                        <span class="w3-text-gray w3-small">Space Separated List</span>
                    </p>
                    
                    <?php if( strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ) echo '<input type="hidden" name="tool" value="' . $tool . '">'; ?>

                    <p>
                        <button type="submit" class="w3-button w3-border w3-border-gray w3-round-large">Submit</button>
                    </p>
                </form>

<?php
            if( $api_result['type'] == "success" )
            {
                foreach( (array) $api_result['answers'] as $type => $answer )
                {
                ?>

                <p class="w3-large w3-border-bottom w3-stretch w3-padding"><b><?php echo $type; ?></b></p>

                <?php
                    if( count( ( array ) $answer['answer'] ) > 0 )
                    {
?>

                <div class="w3-stretch" style="overflow-x: auto; white-space: nowrap;">
                    <table class="w3-table w3-striped">
                        <tr>
                            <th>Hostname</th>
                            <th>Type</th>
                            <th>Class</th>
                            <th>TTL</th>

                            <?php if( $type == "A" || $type == "AAAA" ): ?>

                            <th>Address</th>

                            <?php elseif( $type == "CNAME" ): ?>

                            <th>Hostname</th>

                            <?php elseif( $type == "NS" ): ?>

                            <th>Hostname</th>

                            <?php elseif( $type == "MX" ): ?>

                            <th>Preference</th>
                            <th>Exchange</th>

                            <?php elseif( $type == "SOA" ): ?>

                            <th>Primary NS</th>
                            <th>Responsible Email</th>
                            <th>Serial</th>
                            <th>Refresh</th>
                            <th>Retry</th>
                            <th>Expire</th>
                            <th>Minimum</th>

                            <?php elseif( $type == "TXT" ): ?>

                            <th>Text</th>

                            <?php endif; ?>

                        </tr>

                        <?php foreach( ( array ) $answer['answer'] as $record ): ?>

                        <tr class="w3-monospace">
                            <td><?php echo $record['name']; ?></td>
                            <td><?php echo $record['type']; ?></td>
                            <td><?php echo $record['class']; ?></td>
                            <td><?php echo $networkTools->friendlyTTL( $record['ttl'] ); ?></td>

                            <?php if( $record['type'] == "A" || $record['type'] == "AAAA" ): ?>

                            <td><?php echo $record['address']; ?></td>

                            <?php elseif( $record['type'] == "CNAME" ): ?>

                            <td><?php echo $record['cname']; ?></td>

                            <?php elseif( $record['type'] == "NS" ): ?>

                            <td><?php echo $record['nsdname']; ?></td>

                            <?php elseif( $record['type'] == "MX" ): ?>

                            <td><?php echo $record['preference']; ?></td>
                            <td><?php echo $record['exchange']; ?></td>

                            <?php elseif( $record['type'] == "SOA" ): ?>

                            <td><?php echo $record['mname']; ?></td>
                            <td><?php echo $record['rname']; ?></td>
                            <td><?php echo $record['serial']; ?></td>
                            <td><?php echo $record['refresh']; ?></td>
                            <td><?php echo $record['retry']; ?></td>
                            <td><?php echo $record['expire']; ?></td>
                            <td><?php echo $record['minimum']; ?></td>
                            
                            <?php elseif( $record['type'] == "TXT" ): ?>

                            <td><?php echo $record['text'][0]; ?></td>

                            <?php else: ?>

                            <td><?php echo print_r( $record, true ); ?></td>

                            <?php endif; ?>

                        </tr>

                        <?php endforeach; ?>

                    </table>
                </div>

                <div class="w3-panel w3-stretch">
                    <p>Answer from nameserver <code class="w3-light-gray w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code>.</p>
                </div>

                <?php
                    }
                    else
                    {
                    ?>

                <div class="w3-panel w3-stretch">
                    <p>Nameserver <code class="w3-light-gray w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code> has no <code class="w3-light-gray w3-padding-small w3-round-large"><?php echo $type; ?></code> records for hostname <code class="w3-light-gray w3-padding-small w3-round-large"><?php echo $hostname; ?></code>.</p>
                </div>

                <?php
                    }
                }
            }
            ?>

<?php        
}
elseif( $tool == "whois" )
{
?>

    <h2><b>WHOIS</b> Tool</h2>

    <form class="w3-padding-32">
        <p>
            <label for="hostname">Hostname</label>
            <input type="text" id="hostname" name="hostname" class="w3-input"<?php if( $hostname ) echo ' value="' . $hostname . '"'; ?>>
            <span class="w3-text-gray w3-small">example.tld</span>
        </p>
        
        <?php if( strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ) echo '<input type="hidden" name="tool" value="' . $tool . '">'; ?>

        <p>
            <button type="submit" class="w3-button w3-border w3-border-gray w3-round-large">Submit</button>
        </p>
    </form>

    <?php
    
    if( $api_result['type'] == "success" )
    {
        $result = $api_result['result'];
        $result_lines = explode( "\n", $result );
        $special_result_lines = array( "Domain Name", "Creation Date", "Updated Date", "Registry Expiry Date", "Registrar", "Name Server", "DNSSEC" );

        $result_items = array();

        foreach( $result_lines as $result_line )
        {
            $line_parts = explode( ": ", $result_line );

            if( count( $line_parts ) == 2 )
                $result_items[ $line_parts[ 0 ] ][] = $line_parts[ 1 ];
        }

        echo '<div class="w3-row-padding">';
        foreach( $result_items as $result_item_key => $result_item_value )
        {
            if( in_array( $result_item_key, $special_result_lines ) )
            {
                echo '<div class="w3-half" style="overflow-x: auto;"><b>' . $result_item_key . ':</b>' . ( count( $result_item_value ) > 1 ? '<br>' : ' ' ) . join( "<br>", $result_item_value ) . '</div>';
            }
        }
        echo '</div>';
        ?>

        <pre class="w3-light-gray w3-padding w3-round" style="overflow-x: auto;"><?php echo $result; ?></pre>
    <?php
    }
    elseif( $hostname )
    {
        echo '<pre>' . print_r( $api_result, true ) . '</pre>';
    }

    ?>

<?php
}
else
{
        http_response_code( 404 );
        
        echo '<p>' . $api_result['message'] . '</p>';
}
?>

            </div>
        </main>
        
        <footer class="w3-topbar w3-border-gray w3-light-gray">
            <div class="w3-auto w3-padding w3-small">
                <p>Copyright &copy; <a href="https://davidhunter.scot" target="_blank">David Hunter</a>. Source Code on <a href="https://github.com/DavidHunterScot/NetworkTools" target="_blank">GitHub</a>.</p>
            </div>
        </footer>
    </body>
</html>
