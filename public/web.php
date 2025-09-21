<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . 'NetworkTools.php';

$nameservers = join( ' ', NetworkTools::DEFAULT_NAMESERVERS );

if( isset( $_REQUEST[ 'nameservers' ] ) && $_REQUEST[ 'nameservers' ] )
    $nameservers = $_REQUEST[ 'nameservers' ];

$tool = '';
$query = '';
$type = '';

if( isset( $_REQUEST['tool'] ) && $_REQUEST['tool'] )
    $tool = $_REQUEST['tool'];
if( isset( $_REQUEST['hostname'] ) && $_REQUEST['hostname'] )
    $query = $_REQUEST['hostname'];
if( isset( $_REQUEST['ip_address'] ) && $_REQUEST['ip_address'] )
    $query = $_REQUEST['ip_address'];
if( isset( $_REQUEST['type'] ) && $_REQUEST['type'] )
    $type = $_REQUEST['type'];

if( $tool == 'dns' )
    $endpoint = 'dns/' . $query . '/' . $type . '/' . $nameservers;
else if( $tool == 'rdns' )
    $endpoint = 'rdns/' . $query . '/' . $nameservers;
else if( $tool == 'whois' )
    $endpoint = 'whois/' . $query . '/';

include_once __DIR__ . DIRECTORY_SEPARATOR . 'api.php';

$home_url = strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ? '/' . basename( __FILE__ ) : '/';
$dns_url = strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ? '/' . basename( __FILE__ ) . '?tool=dns' : '/dns';
$rdns_url = strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ? '/' . basename( __FILE__ ) . '?tool=rdns' : '/rdns';
$whois_url = strpos( $_SERVER['REQUEST_URI'], basename( __FILE__ ) ) ? '/' . basename( __FILE__ ) . '?tool=whois' : '/whois';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Network Tools</title>
        
        <!-- <link rel="stylesheet" type="text/css" href="/assets/w3css/4.15/w3.css"> -->
        <link rel="stylesheet" type="text/css" href="/assets/webfonts/poppins/poppins.css">

        <style type="text/css">
            *, *::before, *::after { box-sizing: border-box; }
            html, body { font-family: "Poppins", sans-serif; font-size: 1rem; margin: 0; padding: 0; background-color: #edf2f7; color: #000; line-height: 1.5; }
            header, nav, footer, .tools .tool, form { background-color: #fff; color: #000; }
            header h1 { font-size: 1.3rem; margin: 0; padding: 0; }
            .container { max-width: 1200px; margin-left: auto; margin-right: auto; padding: 1rem; }
            nav .container { display: flex; flex-direction: row; gap: 1rem; }
            nav a { text-decoration: none; color: #000; font-weight: 600; }
            nav a:hover, nav a.current { color: #4a68cf; }
            main { margin-top: 3rem; margin-bottom: 3rem; }
            .tools { display: flex; gap: 1.5rem; flex-wrap: wrap; flex-direction: column; justify-content: space-between; margin-top: 3rem; }
            .tools .tool { display: flex; flex-direction: column; gap: 1rem; width: 100%; padding: 1.5rem; border-radius: 0.6rem; text-align: center; text-decoration: none; border: 1px solid #E2E8F0; }
            .tools .tool:hover { border-color: #4a68cf; }
            .tools .tool .icon img { max-width: 2.5rem; }
            .tools .tool .title { font-size: 1.25rem; font-weight: bold; }
            .tools .tool .description { font-size: 1rem; font-weight: normal; }

            form { padding: 1rem 2rem; border-radius: 1rem; }
            label { color: #000; }
            input[ type="text" ] { padding: 0.6rem; font-size: 1.3rem; display: block; border: none; border-bottom: 1px solid #ccc; width: 100%; }
            select { padding: 0.6rem; font-size: 1.3rem; width: 100%; border: none; border-bottom-width: medium; border-bottom-style: none; border-bottom-color: currentcolor; border-bottom: 1px solid #ccc; }
            button[ type="submit" ] { cursor: pointer; background-color: #38A169; padding: 0 24px; line-height: 2.5rem; font-size: 1rem; border-radius: 0.375rem; font-weight: 600; border-width: 0; color: #fff; }
            button[ type="submit" ]:hover { background-color: #2F855A; }
            form span { color: #888; font-size: 0.9rem; }

            .results-container { margin-bottom: 3rem; }
            .results-container code { background-color: #fff; padding: 0.6rem; border-radius: 0.6rem; }
            table { width: 100%; border-width: 0; border-collapse: collapse; }
            table tr { margin: 0; padding: 0; background-color: #f1f1f1; }
            table tr:nth-child( 2n ) { background-color: #fff; }
            table tr th, table tr td { padding: 1rem; margin: 0; text-align: left; }

            pre { background-color: #fff; padding: 1rem; border-radius: 1rem; }

            h2 { font-size: 2rem; }
            h2 img { max-width: 3rem; vertical-align: middle; }

            @media( prefers-color-scheme: dark )
            {
                html, body { background-color: #242c3a; color: #ffffffeb; }
                header, nav, footer, .tools .tool, form { background-color: #242c3a; color: #ffffffeb; }
                html, body { background-color: #1A202C; }
                nav a { color: #fff; }
                nav a:hover, nav a.current { color: #718ada; }
                .tools .tool { border-color: #1A202C; }
                .tools .tool:hover { border-color: #718ada; }
                label { color: #fff; font-weight: 600; }
                input, select { background-color: #2D3748; color: #ffffffeb; }
                form span { color: #ccc; }

                .results-container code { background-color: #242c3a; }
                table tr { background-color: #1A202C; }
                table tr:nth-child( 2n ) { background-color: #242c3a; }

                pre { background-color: #242c3a; }
            }

            @media( min-width: 800px )
            {
                .tools { flex-direction: row; }
                .tools .tool { width: calc( 50% - 0.75rem ); }
            }
        </style>
    </head>
    
    <body>
        <header>
            <div class="container">
                <h1 class="w3-large"><b>Network Tools</b></h1>
            </div>
        </header>

        <nav>
            <div class="container">
                <a class="w3-bar-item w3-button w3-bottombar w3-border-none w3-hover-none<?php if( $tool == '' ) echo ' current'; ?>" href="<?php echo $home_url; ?>">Home</a>
                <a class="w3-bar-item w3-button w3-bottombar w3-border-none w3-hover-none<?php if( $tool == 'dns' ) echo ' current'; ?>" href="<?php echo $dns_url; ?>">DNS</a>
                <a class="w3-bar-item w3-button w3-bottombar w3-border-none w3-hover-none<?php if( $tool == 'rdns' ) echo ' current'; ?>" href="<?php echo $rdns_url; ?>">rDNS</a>
                <a class="w3-bar-item w3-button w3-bottombar w3-border-none w3-hover-none<?php if( $tool == 'whois' ) echo ' current'; ?>" href="<?php echo $whois_url; ?>">WHOIS</a>
            </div>
        </nav>
        
        <main>
            <div class="container">

<?php

if( $tool == "" )
{
?>

                <p><?php echo $api_result['message']; ?></p>

                <div class="tools">
                    <a href="<?php echo $dns_url; ?>" class="tool">
                        <div class="icon"><img src="/assets/images/icons/search.svg"></div>
                        <div class="title">DNS</div>
                        <div class="description">Lookup the DNS records for a given hostname.</div>
                    </a>

                    <a href="<?php echo $rdns_url; ?>" class="tool">
                        <div class="icon"><img src="/assets/images/icons/info.svg"></div>
                        <div class="title">rDNS</div>
                        <div class="description">Lookup the hostname associated with an IP address.</div>
                    </a>

                    <a href="<?php echo $whois_url; ?>" class="tool">
                        <div class="icon"><img src="/assets/images/icons/file.svg"></div>
                        <div class="title">WHOIS</div>
                        <div class="description">Lookup the public WHOIS record for a domain name.</div>
                    </a>
                </div>

<?php
}
elseif( $tool == "dns" )
{
?>

                <h2><img src="/assets/images/icons/search.svg"> <b>DNS</b> Tool</h2>

                <form class="w3-padding-32">
                    <p>
                        <label for="hostname">Hostname</label>
                        <input type="text" id="hostname" name="hostname" class="w3-input"<?php if( $hostname ) echo ' value="' . $hostname . '"'; ?>>
                        <span class="w3-text-gray w3-small">example.tld</span>
                    </p>
                    
                    <p>
                        <label for="type">Type</label>
                        <select class="w3-padding-small w3-select" name="type" id="type">
                            <option value="ALL"<?php if( $type == '' ) echo 'selected'; ?>>ALL</option>
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

                <div class="w3-stretch results-container" style="overflow-x: auto; white-space: nowrap;">
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

                <div class="w3-panel w3-stretch results-container">
                    <p>Answer from nameserver <code class="background-alt w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code>.</p>
                </div>

                <?php
                    }
                    else
                    {
                    ?>

                <div class="w3-panel w3-stretch results-container">
                    <p>Nameserver <code class="background-alt w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code> has no <code class="background-alt w3-padding-small w3-round-large"><?php echo $type; ?></code> records for hostname <code class="background-alt w3-padding-small w3-round-large"><?php echo $hostname; ?></code>.</p>
                </div>

                <?php
                    }
                }
            }
            else if( $api_result[ 'type' ] == 'error' && isset( $api_result[ 'message' ] ) && isset( $_REQUEST[ 'hostname' ] ) )
            {
            ?>
                <div class="w3-stretch" style="overflow-x: auto; white-space: nowrap;">
                    <table class="w3-table w3-striped">
                        <tr>
                            <th>Type</th>
                            <th>Message</th>
                        </tr>

                        <tr>
                            <td>Error</td>
                            <td><?php echo $api_result[ 'message' ]; ?></td>
                        </tr>
                    </table>
                </div>

                <p>&nbsp;</p>
            <?php
            }
            ?>

<?php        
}
elseif( $tool == "rdns" )
{
?>
    <h2><img src="/assets/images/icons/info.svg"> <b>rDNS</b> Tool</h2>

    <form class="w3-padding-32">
        <p>
            <label for="ip_address">IP Address</label>
            <input type="text" id="ip_address" name="ip_address" class="w3-input"<?php if( $ip_address ) echo ' value="' . $ip_address . '"'; ?>>
            <span class="w3-text-gray w3-small">127.0.0.1</span>
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
                    <th>IP Address</th>
                    <th>Type</th>
                    <th>Class</th>
                    <th>TTL</th>
                    <th>Hostname</th>
                </tr>

                <?php foreach( ( array ) $answer['answer'] as $record ): ?>

                <tr class="w3-monospace">
                    <td><?php echo $ip_address; ?></td>
                    <td><?php echo $record['type']; ?></td>
                    <td><?php echo $record['class']; ?></td>
                    <td><?php echo $networkTools->friendlyTTL( $record['ttl'] ); ?></td>
                    <td><?php echo $record['ptrdname']; ?></td>
                </tr>

                <?php endforeach; ?>

            </table>
        </div>

        <div class="w3-panel w3-stretch">
            <p>Answer from nameserver <code class="background-alt w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code>.</p>
        </div>

        <?php
            }
            else
            {
            ?>

        <div class="w3-panel w3-stretch">
            <p>Nameserver <code class="background-alt w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code> has no <code class="background-alt w3-padding-small w3-round-large">PTR</code> records for hostname <code class="background-alt w3-padding-small w3-round-large"><?php echo $hostname; ?></code>.</p>
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

    <h2><img src="/assets/images/icons/file.svg"> <b>WHOIS</b> Tool</h2>

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

        <pre class="background-alt w3-padding w3-round" style="overflow-x: auto;"><?php echo $result; ?></pre>
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
        
        <footer class="w3-topbar w3-border-gray background-alt">
            <div class="container w3-small">
                <p>&nbsp;</p>
            </div>
        </footer>
    </body>
</html>
