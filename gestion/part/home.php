		<div class="titre_colonne" style="margin-top:5px">🔥 Sites à valider (ou pas) </div>
		
		<table width="100%" id="listisite">
			<thead>
			<tr>
				<th>⚙️ Site <a href="gestion/?order=titre-ASC">&uArr;</a> <a href="gestion/?order=titre-DESC">&dArr;</a></th>
				<th>🌐 URL</th>
				<th>🔗 BL</th>
				<th align="center" style="text-align:center">🗑</th>
				
			</tr>
			</thead>
			<?php 
			
			if(!empty($_GET['order'])){
				$get_order = str_replace('-', ' ', strip_tags($_GET['order']));
			}
			else {
				$get_order = 'url_retour DESC, id_site ASC';
			}
			
			$site = rcp_site('', '', 'ORDER BY '.$get_order.'', '2', '', '');
			
			if(is_array($site)) {
				echo "<tbody>";

					require_once dirname(__FILE__).'/../../bs-includes/email_blacklist.php';
					foreach ($site as $s) { 
						$mailauteur = $s['mail_auteur'];
						if (in_array($mailauteur, $email_colimateur)) 
						{$spot_relou = "⚠️";} 
						else 
						{$spot_relou = "";}
						$mister_bl = $s['url_retour']; 
					?>
						<tr <?php if (!empty($mister_bl)){ echo "style='background:yellow;'"; } ?>>
							<td style="text-align:left;">
								<a class="homethuglife" href="gestion/?act=1&id=<?php echo $s['id_site']; ?>" class="settingsite">⚙️ <?php echo stripslashes($s['titre']); ?></a>
								✉️ <?php echo $spot_relou; ?><a href="mailto:<?php echo $mailauteur; ?>" class='maildepot'><?php echo $mailauteur; ?></a>
								<br />
								📅 <em style='font-size:12px;'><?php $date = new DateTime($s['f_date']);	echo $date->format('d/m/Y'); ?></em>
							</td>
							<td>
								<a href="<?php echo $s['url'];?>" target='_blank' rel='nofollow noreferrer noopener' class='newtab'><?php echo url_www($s['url']); ?></a>
							</td>	
							<td>
								<?php if (empty($mister_bl)){ echo '⭕'; } else { echo "<a href='$mister_bl' target='_blank' rel='nofollow noreferrer noopener'>✅</a>"; } ?>
							</td>
							<td style="text-align:center">
								<a href="gestion/?act=1&f=31&id=<?php echo $s['id_site']; ?>" onclick="return confirm('Etes-vous sur de vouloir supprimer <?php echo stripslashes($s['titre']); ?> de votre annuaire ?')">🗑️</a>
							</td>
						</tr>
					
					<?php
					unset($mailauteur); 
					} 
				echo "</tbody>";
			} ?>
			</table>
			
			<?php 
			$mesrelances = mysqli_query($connexion, "SELECT * FROM ".TABLE_RELANCE." n INNER JOIN ".TABLE_SITE." f ON f.id_site = n.id_site WHERE n.date_ticket < date_sub(curdate(), interval 14 day)");
			if(mysqli_num_rows($mesrelances)>0)	{ 
				$tableaurelance = '
				<div class="titre_colonne" style="margin-top:5px">🔻 Sites à downgrader</div>
				
				<table width="100%">
				<thead style="background:#eee;">
				<tr>
					<th style="text-align:center">ID</th>
					<th style="text-align:center">🌍 Site </th>
					<th style="text-align:center">️🔗</th>
					<th style="text-align:center">️📅 Date</th>
					<th style="text-align:center">Downgrader</th>
					<th style="text-align:center">Clore Ticket</th>
					
				</tr></thead>';
				
				foreach ($mesrelances as $mcp) { 
				
				$date_ouverture_ticket = date_create($mcp['date_ticket']);
				$date_jour = date_create('now');
				$interval = date_diff($date_ouverture_ticket, $date_jour);
				$belledate = date_format($date_ouverture_ticket, 'd-m-Y');
				$tictacboom = $interval->format('%R%aJ');
				$tictacboom_estim = $interval->format('%a');
					 
				$tableaurelance .='<tr>
					<td style="text-align:center">'.$mcp['id_ticket'].'</td>
					<td style="text-align:center"><a href="gestion/?act=1&id='. $mcp['id_site']. '">⚙️ '. $mcp['url']. '</a> </td>
					<td style="text-align:center"><a href="'. $mcp['url_retour']. '" target="_blank" rel="nofollow noreferrer">🔗</td>
					<td style="text-align:center">'. $belledate. ' ('. $tictacboom .')</td>
					<td style="text-align:center"><a href="gestion/?act=67&f=1&id='.$mcp['id_site'].'&ticket='.$mcp['id_ticket'].'">🔻</a></td>
					<td style="text-align:center"><a href="gestion/?act=67&f=2&id='.$mcp['id_ticket'].'">❌</a></td>	
					</tr>';
				
				} 
				$tableaurelance .= '</table>';
				echo $tableaurelance;
			
			} ?>
			
			
			<div class='titre_colonne' style='margin-top:5px'>🔗 Vos liens utiles</div>
			<ul>
				<li>⚡ Url de la version AMP : <a href="<?php echo $url_annuaire."?amp=1"; ?>" target="_blank"><?php echo $url_annuaire."?amp=1"; ?></a></li>
				<li><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACGUlEQVQ4T53TX0hTURzA8e/d3XbnxLUlpaOkFZW0IQqJkQ8t6GHuodAX3YOi/aGQeqgnrd4iCOrVpIdhovUWIiQsoX9oPkSFPdjIbGrDJ3Pq5pzc/bk3NuWWNkI7T4dzzu9zfr8f5wg/b5a6VUV4jMBBdjZmUWkV5jvs0/8RvH6Vyoww32lXs3PDgWr0ZcdRFn+QCn9EiS9sKx8NMJ++RqHn1oaskpoeIzH6iOTk639CGmAsP4Op2oe+pBxxz2EtKPn1JSvPbqCsRvJCGvDnrljsoKD2EgUnWkA0kFmeI+pvJBOZ/QvRAKmqAaniHOm5ceTxgVyQ3u7C0tKDaCsjsxRmucuLkljahOTvgZJmbcxPfPgeusLdWNufI1r3I38JEHtyMT8g7j2K8YgbyenBcKg2dyj57S3Rvlb0die29iHQiSz7G0mF3mlI3h5IzjqKmroQjGYSI92sBu5S1HAfU00zyclXRHtb8gCCgFRxFjJp5GAAyenF0uyHTIrIg5PoTEXYrr8BJc3CHReqvJJDfjexsh6Lrzu3GOs/jxwcxnplEIOjJpdBNpPijg/orPuI9vhITo1sAaoasDQ93AAuIAdfYHZfpbDuNvLEELGnl9nV1k/2vcQHO1l737cZIFtCZf16CRNDoKroS49hdHrILIaRPw8gubyIJeWkvo+SCn/SgBnAsa2Hv/WQQCj7G08BvTv+kQIhFKHtFxlX1+he0M78AAAAAElFTkSuQmCC"> Url du flux RSS : <a href="<?php echo $url_annuaire."flux/rss.xml"; ?>" target="_blank"><?php echo $url_annuaire."flux/rss.xml"; ?></a></li>
				<li>📂 Url des sitemaps : <a href="<?php echo $url_annuaire."sitemap/sitemap-1.html"; ?>" target="_blank"><?php echo $url_annuaire."sitemap/sitemap-1.html"; ?></a></li>
			</ul>
			
			<div class='titre_colonne' style='margin-top:5px'>📊 Vos Stats</div>
			<p>Votre annuaire contient <?php $site = stat_site (1, '') + stat_site (2, ''); echo $site; ?> sites dont <?php echo stat_site (1, ''); ?> ont été acceptés et <?php echo stat_site (1, 1); ?> ont une fiche.<br /><?php echo stat_site (2, ''); ?> sites sont en attentes de validation.<br />Votre annuaire possède <?php echo stat_section(''); ?> sections réparties dans <?php echo stat_categorie(); ?> catégories.</p>
	
	
			<div class='titre_colonne' style='margin-top:5px'>🧐 F.A.Q. - Les Essentielles</div>
			<h5>Comment changer les paramètres de l'annuaire ?</h5>
			<p>Vous pouvez modifier des paramètres inhérents à votre installation depuis le fichier <u>config.php</u> situé au niveau de la racine de ce script.</p>
			
			<h5>Comment modifier les mails de refus / acceptation ?</h5>
			<p>Rendez-vous dans le <u>dossier</u> config et éditez les fichiers 'mail_inscription_accepte.php' ou 'mail_inscription_refuse.php' à votre convenance. Nous attirons d'ailleurs votre attention sur le fait que ces mails, ainsi que la page de validation d'un site lors de sa soumission, contiennent des liens de parrainages vers des plateformes SEO reconnues dans le métier qui pourront être utiles à vos clients. Ca serait sympa de les laisser, mais libre à vous de les remplacer par vos liens de parrainages ou tout simplement de les supprimer.</p>
			
			<h5>Comment modifier le template ?</h5>
			<ul>
				<li>Rendez-vous dans le dossier 'bs-templates/defaut/' pour trouver tous les fichiers templates.</li>
				<li>Le code situé entre les balises &lt;head> se trouve dans le fichier "bs-include/general.php"</li>
			</ul>
			<p>⚠️ Attention : ce template est 100% compatible AMP (<em>version ultra rapide qui plait beaucoup à Google</em>). En le modifiant pensez à respectez les normes AMP (<a href="https://amp.dev/documentation/guides-and-tutorials/?format=websites" rel="nofollow noreferrer noopener" target="_blank">voir la documentation officielle</a> et le <a href="https://validator.ampproject.org/" rel="nofollow noreferrer noopener" target="_blank">validateur</a>). Vous pouvez vous aider du code variable <strong>&lt;?php if ($amp == true) { } else { } ?></strong> pour insérez dans le thème des éléments spécifiques.</p>
			
			<h5>Comment changer mon mot de passe ?</h5>
			<p>↪️ Générez un nouveau mot de passe en <a href="<?php echo $url_annuaire."config/reset.php"; ?>">cliquant ici</a> !</p>
			
			<h5 id="spam">Comment blacklister / surveiller des emails ?</h5>
			<p>Editez le fichier 'bs-include/email_blacklist.php' pour : </p>
			<ul>
				<li>Exclure une adresse mail précise.</li>
				<li>Exclure un @domaine de manière général pour lequel toute adresse mail sera refusée automatiquement lors de la proposition d'un site. Bye Bye Yopmail !</li>
				<li>Mettre dans le colimateur une adresse mail. Celle-ci n'est pas blacklisté et pourra être utilisé pour proposer un site mais elle vous sera signalé dans l'admin par un logo ⚠️. Pratique pour laisser une DERNIERE chance aux relous qui ont déjà tenté 1 fois de faire passer du duplicate content tout en les gardant à l'oeil !</li>
			</ul>
			
			<h5>Comment modifier les balises title / meta desc ?</h5>
			<p>Rendez-vous dans le fichier 'bs-include/general.php' pour modifier les entêtes de vos pages. Attention à ne pas faire n'importe quoi dans ce fichier.</p>
			
			<p><a href="https://janembart.com/faq/" target="_blank" rel="nofollow">Voir (+) de Questions / Réponse</a></p>
			
		