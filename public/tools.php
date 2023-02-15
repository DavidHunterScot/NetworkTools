<?php

include __DIR__ . DIRECTORY_SEPARATOR . 'NetworkTools.php';

$tool = isset( $_GET['tool'] ) ? $_GET['tool'] : '';
$networkTools = new NetworkTools;

$api = isset( $_GET['api'] );

if( $api )
    header( 'Content-Type: application/json' );
else
{
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Network Tools by David Hunter</title>
        
        <link rel="stylesheet" type="text/css" href="/assets/w3css/4.15/w3.css">
        <link rel="stylesheet" type="text/css" href="/assets/webfonts/poppins/poppins.css">

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
            </div>
        </nav>
        
        <main>
            <div class="w3-auto w3-padding">
<?php
}

switch( $tool )
{
    case '':
        
        $output[ 'type' ] = 'info';
        $output[ 'message' ] = 'Welcome to Network Tools!';
        
        if( $api )
            echo json_encode( $output );
        else
            echo '<p>' . $output['message'] . '</p>';
        
        break;
    case 'dns':
        $hostname = isset( $_GET['hostname'] ) ? $_GET['hostname'] : '';
        $type = isset( $_GET['type'] ) ? $_GET['type'] : 'A';
        $nameservers = isset( $_GET['nameservers'] ) ? explode( ' ', $_GET['nameservers'] ) : NetworkTools::DEFAULT_NAMESERVERS;
        
        $response = $networkTools->dns( $hostname, $type, $nameservers );
        
        if( $api )
            echo json_encode( $response );
        else
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
                        <input type="text" id="type" name="type" class="w3-input"<?php if( $type ) echo ' value="' . $type . '"'; ?>>
                        <span class="w3-text-gray w3-small">Supported Record Types (case sensitive): <?php echo join( ', ', $networkTools->getValidTypes() ); ?></span>
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
            if( $response['type'] == "success" )
            {
                if( count( $response['answer'] ) > 0 )
                {
?>

                <div class="w3-stretch" style="overflow-x: auto; white-space: nowrap;">
                    <table class="w3-table w3-striped">
                        <tr>
                            <th>Name</th>
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

                        <?php foreach( $response['answer'] as $answer ): ?>

                        <tr class="w3-monospace">
                            <td><?php echo $answer['name']; ?></td>
                            <td><?php echo $answer['type']; ?></td>
                            <td><?php echo $answer['class']; ?></td>
                            <td><?php echo $answer['ttl']; ?></td>

                            <?php if( $answer['type'] == "A" || $answer['type'] == "AAAA" ): ?>

                            <td><?php echo $answer['address']; ?></td>

                            <?php elseif( $answer['type'] == "CNAME" ): ?>

                            <td><?php echo $answer['cname']; ?></td>

                            <?php elseif( $answer['type'] == "NS" ): ?>

                            <td><?php echo $answer['nsdname']; ?></td>

                            <?php elseif( $answer['type'] == "MX" ): ?>

                            <td><?php echo $answer['preference']; ?></td>
                            <td><?php echo $answer['exchange']; ?></td>

                            <?php elseif( $answer['type'] == "SOA" ): ?>

                            <td><?php echo $answer['mname']; ?></td>
                            <td><?php echo $answer['rname']; ?></td>
                            <td><?php echo $answer['serial']; ?></td>
                            <td><?php echo $answer['refresh']; ?></td>
                            <td><?php echo $answer['retry']; ?></td>
                            <td><?php echo $answer['expire']; ?></td>
                            <td><?php echo $answer['minimum']; ?></td>
                            
                            <?php elseif( $answer['type'] == "TXT" ): ?>

                            <td><?php echo $answer['text'][0]; ?></td>

                            <?php else: ?>

                            <td><?php echo print_r( $answer, true ); ?></td>

                            <?php endif; ?>

                        </tr>

                        <?php endforeach; ?>

                    </table>
                </div>

                <?php
                }
                else
                    echo '<div class="w3-panel w3-stretch"><p>No <b>' . $type . '</b> records are set for <b>' . $hostname . '</b>.</p></div>';
                ?>

                <div class="w3-panel w3-stretch">
                    <p>Answer from nameserver <code class="w3-light-gray w3-padding-small w3-round-large"><?php echo $response['answer_from']; ?></code>.</p>
                </div>

            <?php
            }
            else
            {
                echo '<p>' . $response['message'] . '</p>';
            }
        }
        
        break;
    default:
        http_response_code( 404 );
        
        $output[ 'type' ] = 'error';
        $output[ 'message' ] = 'Tool Not Found: ' . $tool;
        
        if( $api )
            echo json_encode( $output );
        else
            echo '<p>' . $output['message'] . '</p>';
        
        break;
}

if( ! $api )
{
?>

            </div>
        </main>
        
        <footer class="w3-topbar w3-border-gray w3-light-gray">
            <div class="w3-auto w3-padding w3-small">
                <p>Copyright &copy; <a href="https://davidhunter.scot" target="_blank">David Hunter</a>.</p>
            </div>
        </footer>
    </body>
</html>
<?php
}
