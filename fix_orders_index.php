<?php
$filePath = 'e:\\Saklin Mustak\\All Websites\\WEB\\project-management-web\\resources\\views\\admin\\orders\\index.blade.php';
$content = file_get_contents($filePath);

// Define the correct block for the table row internals
$correctRowInternals = '                                <td><span class="src-tag">{{ $order->service->name ?? \'N/A\' }}</span></td>
                                <td><span class="src-tag">₹{{ number_format($order->order_value, 0) }}</span></td>
                                <td><span class="src-tag" style="background:#10b98120; color:#10b981;">₹{{ number_format($order->advance_payment, 0) }}</span></td>
                                <td>
                                    <span class="status-pill" style="background:{{ ($order->status->color ?? \'#6366f1\') }}20; color:{{ $order->status->color ?? \'#6366f1\' }};">
                                        {{ $order->status->name ?? \'Pending\' }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->createdBy)
                                        <div class="ln">{{ $order->createdBy->name }}</div>
                                        <div class="ls" style="font-size:10px">{{ $order->createdBy->email }}</div>
                                    @else
                                        <div class="ln">System</div>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;flex-direction:column;gap:2px;">
                                        @foreach($order->assignments as $assign)
                                            <div class="ln" style="font-size:12.5px;">{{ $assign->sale->name }}</div>
                                            <div class="ls" style="font-size:10px;">{{ $assign->sale->email }}</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background:rgba(99, 102, 241, 0.1); color:var(--accent); padding:4px 10px; border-radius:6px; font-weight:700; font-family:var(--font-mono); font-size:12px;">
                                        {{ $order->followups_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        @php
                                            $codes = [0=>\'+93\',1=>\'+355\',2=>\'+213\',3=>\'+376\',4=>\'+244\',5=>\'+54\',6=>\'+61\',7=>\'+43\',8=>\'+880\',9=>\'+32\',10=>\'+55\',11=>\'+1\',12=>\'+86\',13=>\'+57\',14=>\'+45\',15=>\'+20\',16=>\'+33\',17=>\'+49\',18=>\'+233\',19=>\'+30\',20=>\'+91\',21=>\'+62\',22=>\'+98\',23=>\'+964\',24=>\'+353\',25=>\'+972\',26=>\'+39\',27=>\'+81\',28=>\'+962\',29=>\'+254\',30=>\'+965\',31=>\'+961\',32=>\'+60\',33=>\'+52\',34=>\'+212\',35=>\'+977\',36=>\'+31\',37=>\'+64\',38=>\'+234\',39=>\'+47\',40=>\'+968\',41=>\'+92\',42=>\'+63\',43=>\'+48\',44=>\'+351\',45=>\'+974\',46=>\'+7\',47=>\'+966\',48=>\'+65\',49=>\'+27\',50=>\'+34\',51=>\'+94\',52=>\'+46\',53=>\'+41\',54=>\'+886\',55=>\'+66\',56=>\'+90\',57=>\'+971\',58=>\'+44\',59=>\'+1\',60=>\'+84\',61=>\'+260\',62=>\'+263\'];
                                            $phoneList = is_array($order->phones) ? $order->phones : [];
                                            $emailList = is_array($order->emails) ? $order->emails : [];
                                            $fullPhones = [];
                                            foreach($phoneList as $p) {
                                                $fullPhones[] = ($codes[$p[\'code_idx\'] ?? \'\'] ?? \'\') . ($p[\'number\'] ?? \'\');
                                            }
                                        @endphp
                                        <style>
                                            .ra-btn.phone:hover {
                                                background: rgba(16, 185, 129, 0.1) !important;
                                                color: #10b981 !important;
                                                border-color: #10b981 !important;
                                            }
                                        </style>
                                        <a href="javascript:void(0)" class="ra-btn phone" 
                                           onclick="handleContactClick(event, \'tel\', {{ json_encode($fullPhones) }})" title="Call Client">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="ra-btn email" 
                                           onclick="handleContactClick(event, \'mailto\', {{ json_encode($emailList) }})" title="Email Client">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>

                                        <a href="{{ route($routePrefix . \'.orders.show\', $order->id) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <a href="{{ route($routePrefix . \'.orders.edit\', $order->id) }}" class="ra-btn" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="{{ route($routePrefix . \'.orders.followup\', $order->id) }}" class="ra-btn" title="Followup"><i class="bi bi-arrow-counterclockwise"></i></a>
                                        <a href="{{ route($routePrefix . \'.payments.create\', $order->id) }}" class="ra-btn" title="Payments"><i class="bi bi-wallet2"></i></a>
                                        @if($routePrefix == \'admin\')
                                        <button class="ra-btn danger" title="Delete" onclick="confirmDelete(\'{{ route($routePrefix . \'.orders.destroy\', $order->id) }}\')"><i class="bi bi-trash-fill"></i></button>
                                        @endif
                                    </div>
                                </td>';

// Correct regex to match the corrupted block
// It starts from the Service cell and ends at the Action cell end
$pattern = '/<td><span class="src-tag">{{ \$order->service->name \?\? \'N\/A\' }}<\/span><\/td>.*?<\/tr>/s';
$replacement = $correctRowInternals . "\n                            </tr>";

$newContent = preg_replace($pattern, $replacement, $content);

if ($newContent !== null) {
    file_put_contents($filePath, $newContent);
    echo "Successfully fixed the file structural integrity.";
} else {
    echo "Failed to fix the file.";
}
