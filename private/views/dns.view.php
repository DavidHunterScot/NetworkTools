<?php $page_title_html = '<img src="/assets/images/icons/search.svg"> <b>DNS</b> Tool'; ?>
<?php include __DIR__ . DIRECTORY_SEPARATOR . 'header.php'; ?>

                <form class="w3-padding-32" method="post">
                    <p>
                        <label for="hostname">Hostname</label>
                        <input type="text" id="hostname" name="hostname" class="w3-input"<?php if( isset( $params[ 'hostname' ] ) && $params[ 'hostname' ] ) echo ' value="' . $params[ 'hostname' ] . '"'; ?>>
                        <span class="w3-text-gray w3-small">example.tld</span>
                    </p>
                    
                    <p>
                        <label for="type">Type</label>
                        <select class="w3-padding-small w3-select" name="type" id="type">
                            <option value="ALL"<?php if( $params[ 'type' ] == '' ) echo ' selected'; ?>>ALL</option>
                            <?php foreach( $params[ 'networkTools' ]->getValidTypes() as $validType ): ?>
                                <option value="<?php echo $validType; ?>"<?php if( $validType == $params[ 'type' ] ) echo ' selected'; ?>><?php echo $validType; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="w3-text-gray w3-small">The DNS record type you wish to query for. Selecting ALL will attempt to retrieve all supported types.</span>
                    </p>
                    
                    <p>
                        <label for="nameservers">Name Server IPs</label>
                        <input type="text" id="nameservers" name="nameservers" class="w3-input"<?php if( isset( $params[ 'nameservers' ] ) && $params[ 'nameservers' ] ) echo ' value="' . join( ' ', $params[ 'nameservers' ] ) . '"'; ?>>
                        <span class="w3-text-gray w3-small">Space Separated List</span>
                    </p>

                    <p>
                        <?php if( isset( $_SESSION[ 'csrf_token' ] ) && $_SESSION[ 'csrf_token' ] ): ?><input type="hidden" name="csrf_token" value="<?php echo $_SESSION[ 'csrf_token' ]; ?>"><?php endif; ?>
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
                            <td><?php echo $params[ 'networkTools' ]->friendlyTTL( $record['ttl'] ); ?></td>

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
                    <p>Nameserver <code class="background-alt w3-padding-small w3-round-large"><?php echo $answer['answer_from']; ?></code> has no <code class="background-alt w3-padding-small w3-round-large"><?php echo $type; ?></code> records for hostname <code class="background-alt w3-padding-small w3-round-large"><?php echo $params[ 'hostname' ]; ?></code>.</p>
                </div>

                <?php
                    }
                }
            }
            ?>

<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>