<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * French strings for the NextGen LMS theme.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'NextGen Academy';
$string['appname'] = 'NextGen Academy';
$string['configtitle'] = 'NextGen Academy';
$string['choosereadme'] = 'NextGen Academy est un thème enfant de Boost. Il ajoute une page d\'accueil publique, un catalogue de cours et une page de vente. Les contenus de cours restent réservés aux personnes inscrites dans Moodle.';
$string['poweredby'] = 'Propulsé par';
$string['poweredbylink'] = 'CumiSolutions';
$string['privacy:metadata'] = 'Le thème NextGen Academy conserve les avis de cours rédigés par les étudiants inscrits.';
$string['privacy:metadata:theme_nextgen_review'] = 'Avis qu\'un étudiant a rédigés sur un cours.';
$string['privacy:metadata:theme_nextgen_review:userid'] = 'L\'étudiant qui a rédigé l\'avis.';
$string['privacy:metadata:theme_nextgen_review:courseid'] = 'Le cours concerné par l\'avis.';
$string['privacy:metadata:theme_nextgen_review:rating'] = 'La note en étoiles donnée avec l\'avis.';
$string['privacy:metadata:theme_nextgen_review:reviewtext'] = 'Le texte de l\'avis.';
$string['privacy:metadata:theme_nextgen_review:timecreated'] = 'Date du premier enregistrement de l\'avis.';
$string['privacy:metadata:theme_nextgen_review:timemodified'] = 'Date de la dernière modification de l\'avis.';

$string['generalsettings'] = 'Général';
$string['footersettings'] = 'Pied de page';
$string['loginsettings'] = 'Connexion';
$string['advancedsettings'] = 'Avancé';

$string['logo'] = 'Logo';
$string['logodesc'] = 'Téléversement facultatif. L\'en-tête et la page de connexion utilisent le logo feuille NextGen Academy fourni.';
$string['favicon'] = 'Favicon';
$string['favicondesc'] = 'Téléversement facultatif. L\'icône du navigateur utilise la même feuille NextGen Academy que l\'en-tête.';

$string['primarycolor'] = 'Couleur principale';
$string['primarycolordesc'] = 'Couleur d\'encre des boutons, des liens et de la navigation. Le logo feuille reste vert.';
$string['secondarycolor'] = 'Couleur secondaire';
$string['secondarycolordesc'] = 'Couleur profonde du pied de page, du panneau de connexion et des grandes bannières.';
$string['accentcolor'] = 'Couleur d\'accent';
$string['accentcolordesc'] = 'Couleur de surbrillance des étapes, de l\'étape de paiement en cours et des graphiques du tableau de bord. Le texte qui s\'y trouve est vert foncé : gardez-la claire.';

$string['customnav'] = 'Liens de l\'en-tête';
$string['customnavdesc'] = 'Un lien par ligne, au format Libellé|/chemin ou Libellé|https://exemple.com. Ces liens s\'ajoutent à la navigation principale de Moodle. Accueil, Tableau de bord, Mes cours, À propos et Contact restent gérés par le thème.';

$string['unaddableblocks'] = 'Blocs non ajoutables';
$string['unaddableblocks_desc'] = 'Noms de blocs séparés par des virgules, qui ne doivent pas être proposés avec ce thème. La valeur par défaut masque les anciens blocs de navigation déjà remplacés par Boost.';

$string['footerabout'] = 'Texte de présentation';
$string['footeraboutdesc'] = 'Courte description affichée dans la colonne de marque du pied de page.';
$string['footeraboutdefault'] = 'Des cours en ligne pratiques pour celles et ceux qui veulent apprendre, s\'exercer et faire avancer leur activité.';

$string['quicklinks'] = 'Liens rapides';
$string['learninglinks'] = 'Apprentissage';
$string['supportlinks'] = 'Assistance';
$string['linklistdesc'] = 'Un lien par ligne, au format Libellé|/chemin ou Libellé|https://exemple.com.';

$string['contactemail'] = 'Adresse de contact';
$string['contactemaildesc'] = 'Affichée dans le pied de page et sur les pages À propos et Contact. Si elle est vide, le pied de page la masque et les pages utilisent l\'adresse d\'assistance du site.';
$string['contactphone'] = 'Téléphone de contact';
$string['contactphonedesc'] = 'Affiché dans le pied de page et sur les pages Contact et À propos. Laissez vide pour le masquer.';
$string['contactaddress'] = 'Adresses des bureaux';
$string['contactaddressdesc'] = 'Un bureau par ligne, au format Libellé|Adresse, par exemple Siège|Face Tradex Nomayous, Yaoundé. Affiché dans le pied de page et sur les pages Contact et À propos.';

$string['facebook'] = 'Facebook';
$string['instagram'] = 'Instagram';
$string['linkedin'] = 'LinkedIn';
$string['youtube'] = 'YouTube';
$string['x'] = 'X';
$string['tiktok'] = 'TikTok';
$string['socialdesc'] = 'URL https complète. Laissez vide pour masquer ce réseau.';
$string['socialfollowon'] = 'Suivre NextGen Academy sur {$a} (s\'ouvre dans un nouvel onglet)';
$string['socialfooterlead'] = 'Cours, événements et conseils pour les entrepreneurs.';
$string['socialfollowtitle'] = 'Nous suivre';
$string['socialfollowlead'] = 'Actualités et nouvelles des cours de Next Gen Entrepreneurship Development Ltd.';
$string['aboutsocialtitle'] = 'Nous suivre';
$string['aboutsociallead'] = 'Actualités et nouvelles des cours de Next Gen Entrepreneurship Development Ltd.';

$string['copyright'] = 'Mention de droit d\'auteur';
$string['copyrightdesc'] = 'Laissez vide pour utiliser l\'année et le nom du site.';
$string['copyrightdefault'] = '© {$a->year} {$a->name}';
$string['privacyurl'] = 'URL de la politique de confidentialité';
$string['termsurl'] = 'URL des conditions d\'utilisation';
$string['policyurldesc'] = 'Chemin du site ou URL https complète. Laissez vide pour utiliser la page intégrée.';

$string['loginbackgroundimage'] = 'Image de fond de la connexion';
$string['loginbackgroundimagedesc'] = 'Photographie de la carte à côté des formulaires de connexion et d\'inscription. Utilisez une image paysage ou portrait d\'au moins 1 200 pixels de haut. Si elle est vide, la photographie des apprenants du thème est utilisée.';

$string['customcss'] = 'CSS personnalisé';
$string['customcssdesc'] = 'CSS ajouté après les styles du thème. Les changements s\'appliquent après la purge des caches.';

$string['frontpagesettings'] = 'Page d\'accueil';
$string['herotitle'] = 'Titre principal';
$string['herotitle_desc'] = 'Titre principal de la page d\'accueil du site.';
$string['herotitledefault'] = 'Transformez vos idées d\'entreprise en véritables opportunités';
$string['herotext'] = 'Texte d\'introduction';
$string['herotext_desc'] = 'Phrase d\'appui sous le titre.';
$string['herotextdefault'] = 'Des cours pratiques d\'entrepreneuriat, un accompagnement d\'experts et des ressources d\'apprentissage pour aider les étudiants africains et internationaux, les aspirants entrepreneurs et les chefs d\'entreprise à lancer, gérer et développer des activités prospères.';
$string['heroprimary'] = 'Bouton principal';
$string['herosecondary'] = 'Bouton secondaire';
$string['herosecondarydesc'] = 'Les visiteurs voient Commencer à apprendre maintenant, qui ouvre le formulaire de connexion. Après la connexion, le même bouton affiche Continuer à apprendre et ouvre le tableau de bord.';
$string['herobuttondesc'] = 'Une ligne, au format Libellé|/chemin. Utilisez une ancre #, par exemple #why-nextgen, pour descendre dans la page.';
$string['heroprimarydefault'] = 'Explorer les cours|/course/index.php';
$string['herosecondarydefault'] = 'Commencer à apprendre maintenant|/login/index.php';
$string['herostartnow'] = 'Apprendre maintenant';
$string['testimonials'] = 'Témoignages';
$string['testimonialsdesc'] = 'Une citation par ligne, au format Citation|Attribution. Laissez vide pour masquer la section.';
$string['testimonialsdefault'] = "Le travail pratique était assez précis pour m\'en servir dans mon métier la même semaine.|Participant au programme\nJe voyais ce que j\'avais terminé et ce qui restait à faire.|Apprenant\nLa structure du cours a permis d\'enseigner à une cohorte sans perdre personne.|Responsable de cours";

$string['viewcourse'] = 'Voir le cours';
$string['platformstats'] = 'Chiffres de la plateforme';
$string['statcourses'] = 'Cours en ligne';
$string['statinstructors'] = 'Formateurs';
$string['statcertification'] = 'Certification';
$string['statmembership'] = 'Adhésion';
$string['statcountplus'] = '{$a}+';
$string['statpercent'] = '{$a} %';
$string['coursesavailable'] = 'Cours';
$string['categoriesavailable'] = 'Catégories';
$string['whyheading'] = 'Pourquoi apprendre avec NextGen Academy';
$string['reason1title'] = 'Prenez le temps d\'apprendre de nouvelles compétences';
$string['reason1text'] = 'Terminez avec quelque chose que vous pouvez appliquer.';
$string['reason2title'] = 'Profitez de l\'apprentissage en ligne et à distance';
$string['reason2text'] = 'Voyez ce qui vient ensuite.';
$string['reason3title'] = 'Obtenez des certificats professionnels';
$string['reason3text'] = 'Une cohorte reste dans un même cours.';
$string['reason4title'] = 'Apprenez auprès des meilleurs formateurs';
$string['reason4text'] = 'Votre place dans le cours est conservée.';
$string['forwardtext'] = '{$a}, de l\'agro-entrepreneuriat et du techpreneuriat à l\'entrepreneuriat vert, culturel et sportif, avec un accompagnement et un mentorat pour créer ou développer une entreprise.';
$string['cataloguesize'] = '{$a} cours pratiques';
$string['cataloguesizeone'] = 'un cours pratique';
$string['cataloguesizenone'] = 'des cours pratiques';
$string['instructorintro'] = 'Chaque cours est animé par un formateur que vous pouvez consulter avant de vous inscrire. Il prépare les activités, reste avec la classe et répond aux questions dans le cours.';
$string['instructorpoint1'] = 'Voyez qui anime un cours avant de le rejoindre';
$string['instructorpoint2'] = 'Le même formateur reste avec ce cours';
$string['instructorpoint3'] = 'Les questions vont à la personne qui a préparé le travail';
$string['instructorsempty'] = 'Aucun formateur n\'est encore indiqué sur un cours publié.';
$string['allcourses'] = 'Tous les cours';
$string['featuredheading'] = 'Cours à la une';
$string['bestseller'] = 'Meilleure vente';
$string['commercialfields'] = 'Commercial';
$string['fieldprice'] = 'Prix';
$string['fieldpricedesc'] = 'Montant de repli uniquement. Un nombre est affiché dans la devise des frais du site. Lorsqu\'une inscription payante existe, ce coût et cette devise sont affichés à la place.';
$string['fieldrating'] = 'Note';
$string['fieldratingdesc'] = 'Note moyenne de 0 à 5, par exemple 4,5.';
$string['fieldreviews'] = 'Avis';
$string['fieldreviewsdesc'] = 'Nombre de notes affiché à côté des étoiles.';
$string['fieldlevel'] = 'Niveau';
$string['fieldleveldesc'] = 'Affiché sur la page du cours, par exemple Débutant.';
$string['fieldlength'] = 'Durée';
$string['fieldlengthdesc'] = 'Affichée sur la page du cours, par exemple 4 semaines.';
$string['fieldeffort'] = 'Charge de travail';
$string['fieldeffortdesc'] = 'Affichée sur la page du cours, par exemple 8 heures par semaine.';
$string['fieldcertificate'] = 'Certificat';
$string['fieldcertificatedesc'] = 'Affiché sur la page du cours, par exemple Oui.';
$string['fieldlanguage'] = 'Langue';
$string['fieldlanguagedesc'] = 'Affichée sur la page du cours. Laissez vide pour utiliser la langue du cours ou du site.';
$string['fieldbestseller'] = 'Meilleure vente';
$string['fieldbestsellerdesc'] = 'Afficher le badge meilleure vente sur la fiche du cours.';
$string['ratinglabel'] = 'Noté {$a->rating} sur 5';
$string['ratinglabelreview'] = 'Noté {$a->rating} sur 5 à partir de {$a->reviews} avis';
$string['ratinglabelreviews'] = 'Noté {$a->rating} sur 5 à partir de {$a->reviews} avis';
$string['browseall'] = 'Voir tous les cours';
$string['nocoursesyet'] = 'Les cours publiés apparaîtront ici.';
$string['categoriesheading'] = 'Parcourir par catégorie';
$string['categorycoursecount'] = '{$a} cours';
$string['categorycoursecounts'] = '{$a} cours';
$string['partnersheading'] = 'Nous collaborons avec fierté';
$string['partnerslead'] = 'Partenaires publics, de formation et d\'entreprise qui soutiennent ce travail au Cameroun.';
$string['partnerminefop'] = 'Ministère de l\'Emploi et de la Formation professionnelle';
$string['partnerminpmeesa'] = 'Ministère des Petites et Moyennes Entreprises';
$string['partnerapme'] = 'Agence de Promotion des PME';
$string['partneresrc'] = 'Entrepreneurship and Social Research Centre-Cameroon';
$string['partnercga'] = 'Centre de Gestion Agréé (CGA)';
$string['partneristp'] = 'In-Service Training Programme (ISTP) of the CBC-PCC-EZE- Cameroon';
$string['partneristpshort'] = 'ISTP';
$string['partneristpsub'] = 'CBC-PCC-EZE Cameroun';
$string['howheading'] = 'Comment ça marche';
$string['step1title'] = 'Choisissez un cours';
$string['step1text'] = 'Parcourez le catalogue et ouvrez le cours qui correspond à ce que vous voulez apprendre.';
$string['step2title'] = 'Inscrivez-vous et commencez';
$string['step2text'] = 'Rejoignez le cours avec la méthode d\'inscription prévue, puis ouvrez la première activité.';
$string['step3title'] = 'Suivez votre progression';
$string['step3text'] = 'L\'achèvement, les notes et le sommaire du cours montrent ce qui est fait et ce qui reste.';
$string['instructorsheading'] = 'Les personnes qui enseignent sur NextGen Academy';
$string['benefitsheading'] = 'Ce à quoi vous pouvez vous attendre';
$string['benefit1title'] = 'Une progression qui reste en place';
$string['benefit1text'] = 'Revenez plus tard : le cours sait encore quelles activités vous avez terminées.';
$string['benefit2title'] = 'Les activités au même endroit';
$string['benefit2text'] = 'Leçons, tests, devoirs et discussions se trouvent dans le même cours.';
$string['benefit3title'] = 'De la place pour une cohorte';
$string['benefit3text'] = 'Les formateurs peuvent inscrire un groupe, suivre l\'achèvement et répondre aux questions dans le cours.';
$string['benefit4title'] = 'Un espace de travail plus calme';
$string['benefit4text'] = 'La navigation, la recherche et vos cours restent dans le même en-tête sur chaque page.';
$string['quotesheading'] = 'De la communauté d\'apprentissage';
$string['ctaheading'] = 'Commencez par un cours qui vous convient';
$string['ctatext'] = 'Parcourez ce qui est disponible, ou connectez-vous pour continuer à apprendre.';
$string['ctaguest'] = 'Commencer à apprendre aujourd\'hui';
$string['ctaimagealt'] = 'Des étudiants qui apprennent ensemble';

$string['loginkicker'] = 'NextGen Academy';
$string['logintitle'] = 'Développez vos compétences. Construisez votre avenir.';
$string['logindescription'] = 'Formation pratique à l\'entrepreneuriat pour les jeunes, les étudiants et les professionnels.';
$string['authbrowse'] = 'Parcourir le catalogue de cours';
$string['authwelcome'] = 'Bienvenue sur {$a}';
$string['authswitchlabel'] = 'Se connecter ou créer un compte';
$string['authswitchlogin'] = 'Connexion';
$string['authswitchregister'] = 'Inscription';
$string['authloginheading'] = 'Connectez-vous à votre compte';
$string['authloginlead'] = 'Reprenez vos cours, suivez votre progression et repartez là où vous vous étiez arrêté.';
$string['authsignupheading'] = 'Créez votre compte';
$string['authsignuplead'] = 'Rejoignez NextGen Academy pour vous inscrire à des cours pratiques d\'entrepreneuriat et suivre votre progression.';
$string['cataloguekicker'] = 'Catalogue de cours';
$string['cataloguetitle'] = 'Trouver un cours';
$string['cataloguetext'] = 'Cherchez dans le catalogue, choisissez une catégorie ou parcourez les cours ci-dessous.';
$string['catalogueempty'] = 'Aucun cours n\'est encore publié. Lorsqu\'un cours sera ajouté, il apparaîtra dans ce catalogue.';
$string['catalogueemptycategory'] = 'Aucun cours n\'est encore publié dans cette catégorie.';
$string['catalogueemptysearch'] = 'Aucun cours ne correspond à ces filtres.';
$string['cataloguesearch'] = 'Rechercher des cours';
$string['cataloguesearchbutton'] = 'Rechercher';
$string['catalogueadvanced'] = 'Recherche avancée';
$string['catalogueadvancedcount'] = 'Recherche avancée ({$a})';
$string['cataloguefilterany'] = 'Tous';
$string['catalogueprice'] = 'Prix';
$string['cataloguefee'] = 'Frais';
$string['cataloguefree'] = 'Gratuit';
$string['cataloguepaid'] = 'Payant';
$string['catalogueapply'] = 'Appliquer les filtres';
$string['catalogueclear'] = 'Effacer les filtres';
$string['catalogueclearone'] = 'Retirer le filtre';
$string['catalogueresult'] = '1 cours';
$string['catalogueresults'] = '{$a} cours';
$string['footercontact'] = 'Contact';
$string['footersocial'] = 'Nous suivre';
$string['courseabout'] = 'À propos du cours';
$string['courseoutcomes'] = 'Ce que vous allez apprendre';
$string['coursesubjects'] = 'Sujets abordés dans ce cours';
$string['courserequirements'] = 'Prérequis';
$string['courseincludes'] = 'Ce cours comprend';
$string['courseenrol'] = 'S\'inscrire';
$string['coursecontinue'] = 'Continuer';
$string['courseprice'] = 'Prix';
$string['checkouttitle'] = 'Terminer l\'inscription';
$string['checkoutback'] = 'Retour au cours';
$string['checkoutpaytitle'] = 'Paiement';
$string['checkoutaccount'] = 'Connecté en tant que';
$string['checkoutprofiletitle'] = 'Confirmez vos informations';
$string['checkoutprofiletext'] = 'Avant de payer, confirmez le nom et l\'adresse de courriel de votre compte. Le paiement utilise ces informations.';
$string['checkoutprofilecustomtext'] = 'Votre compte a encore besoin de quelques champs de profil obligatoires avant le paiement. Complétez-les, puis vous reviendrez ici.';
$string['checkoutprofilecta'] = 'Continuer';
$string['checkoutemaillocked'] = 'Ce site n\'autorise pas le changement d\'adresse de courriel ici. Utilisez l\'adresse actuelle de votre compte.';
$string['checkoutemailunchanged'] = 'Votre nom a été enregistré. Sur ce site, le changement d\'adresse de courriel doit être confirmé depuis votre page de profil.';
$string['checkoutfeenote'] = 'Choisissez comment vous souhaitez payer pour rejoindre ce cours.';
$string['checkoutpaymethods'] = 'Payez en toute sécurité par Mobile Money (MTN ou Orange) ou par carte (Visa ou Mastercard).';
$string['checkouttrust'] = 'Paiement sécurisé. Vous accédez au cours dès que le paiement est confirmé.';
$string['securepayment'] = 'Paiement sécurisé';
$string['paymobile'] = 'Mobile Money (MTN et Orange)';
$string['paycards'] = 'Cartes (Visa et Mastercard)';
$string['paymobiletitle'] = 'Mobile Money';
$string['paymobilebrands'] = 'MTN et Orange';
$string['paycardstitle'] = 'Cartes';
$string['paycardsbrands'] = 'Visa et Mastercard';
$string['securepaymentnote'] = 'Vous pouvez ouvrir le cours dès que le paiement est confirmé.';
$string['checkoutstepcourse'] = 'Cours';
$string['checkoutstepaccount'] = 'Compte';
$string['checkoutsteppayment'] = 'Paiement';
$string['checkoutguesttext'] = 'Connectez-vous pour continuer l\'inscription et le paiement de ce cours.';
$string['checkoutguestcta'] = 'Se connecter pour s\'inscrire';
$string['checkoutclosedtext'] = 'L\'inscription n\'est pas disponible pour ce cours pour le moment.';
$string['checkoutemptytitle'] = 'Étape suivante';
$string['enrolsuccesstitle'] = 'Vous êtes inscrit';
$string['enrolsuccesstext'] = 'Le paiement est terminé. Ouvrez le contenu du cours pour commencer.';
$string['enrolsuccesscta'] = 'Commencer à apprendre';

$string['courseactivities'] = '{$a} activités';
$string['courseactivity'] = '1 activité';
$string['coursesection'] = '1 section';
$string['coursesections'] = '{$a} sections';
$string['coursestarts'] = 'Début';
$string['courseends'] = 'Fin';
$string['courselanguage'] = 'Langue';
$string['courselength'] = 'Durée';
$string['courselevel'] = 'Niveau';
$string['courseeffort'] = 'Charge de travail';
$string['coursecertificate'] = 'Certificat';
$string['coursecategory'] = 'Catégorie';
$string['coursestructure'] = 'Structure';
$string['courseformat_topics'] = 'Thématiques';
$string['courseformat_weeks'] = 'Hebdomadaire';
$string['courseformat_singleactivity'] = 'Activité unique';
$string['courseformat_social'] = 'Social';
$string['coursesectionslabel'] = 'Sections';
$string['courseactivitieslabel'] = 'Activités';
$string['courseresourceslabel'] = 'Ressources';
$string['coursecompletion'] = 'Achèvement';
$string['coursecompletiontracked'] = 'Suivi';
$string['coursedetails'] = 'Détails du cours';
$string['certificateincluded'] = 'Certificat d\'achèvement';
$string['coursecontent'] = 'Contenu du cours';
$string['expandall'] = 'Tout développer';
$string['collapseall'] = 'Tout réduire';
$string['showless'] = 'Afficher moins';
$string['showmore'] = 'Afficher plus';
$string['contentsearch'] = 'Rechercher';
$string['contentcounts'] = 'Sections : {$a->sections} • Activités : {$a->activities} • Ressources : {$a->resources}';
$string['contentempty'] = 'Ce cours ne liste encore aucune section.';
$string['requestquote'] = 'Demander un devis';
$string['sharecourse'] = 'Partager ce cours';
$string['copylink'] = 'Copier le lien';
$string['linkcopied'] = 'Lien copié';
$string['studentcount'] = '{$a} étudiants';
$string['studentcountone'] = '1 étudiant';
$string['lastupdated'] = 'Dernière mise à jour {$a}';
$string['taboverview'] = 'Aperçu';
$string['tabcertificate'] = 'Certificat';
$string['tabinstructors'] = 'Formateurs';
$string['tabreviews'] = 'Avis';
$string['certificateempty'] = 'Ce cours ne comprend pas de certificat.';
$string['certificatenone'] = 'Vous pouvez tout de même terminer les activités de ce cours. Aucun certificat n\'est délivré à la fin.';
$string['certificatelead'] = 'NextGen Academy délivre un certificat d\'achèvement pour ce cours. Il est inclus dans votre inscription, et vous le recevez après avoir terminé les activités requises.';
$string['certificatefactname'] = 'Il indique votre nom, ce cours et la date à laquelle vous le terminez.';
$string['certificatefactfee'] = 'Le certificat n\'entraîne pas de frais supplémentaires.';
$string['certificatefactsample'] = 'L\'aperçu est un exemple. Ce n\'est pas le certificat qui vous est délivré.';
$string['certificatenonefinish'] = 'Terminer les activités ne crée pas de certificat.';
$string['certificatenonefee'] = 'Des frais de cours n\'ajoutent pas de certificat à ce cours.';
$string['certificatesample'] = 'Exemple de certificat';
$string['certificatesamplemark'] = 'Exemple';
$string['certificatesamplenote'] = 'Voici un exemple du certificat délivré lorsque vous terminez ce cours.';
$string['certificatetitle'] = 'Certificat d\'achèvement';
$string['certificatesubtitle'] = 'Délivré par';
$string['certificatecertifies'] = 'Ceci certifie que';
$string['certificatelearner'] = 'Nom de l\'apprenant';
$string['certificatecompleted'] = 'a terminé avec succès';
$string['certificatesampledate'] = 'Date d\'achèvement';
$string['certificatedatelabel'] = 'Date';
$string['certificateinstructorname'] = 'Nom du formateur';
$string['certificateinstructorlabel'] = 'Formateur';
$string['reviewempty'] = 'Aucun avis n\'a été ajouté pour ce cours.';
$string['reviewwrite'] = 'Rédiger un avis';
$string['reviewrating'] = 'Votre note';
$string['reviewstars'] = '{$a} étoiles';
$string['reviewtext'] = 'Votre avis';
$string['reviewsubmit'] = 'Envoyer l\'avis';
$string['reviewupdate'] = 'Mettre à jour l\'avis';
$string['reviewlogin'] = 'Connectez-vous pour rédiger un avis';
$string['reviewsubmitted'] = 'Votre avis a été enregistré.';
$string['reviewupdated'] = 'Votre avis a été mis à jour.';
$string['reviewenrol'] = 'Inscrivez-vous à ce cours avant de laisser un avis.';
$string['reviewincomplete'] = 'Terminez tous les modules de ce cours avant de laisser un avis.';
$string['reviewfinished'] = 'Vous avez terminé ce cours. Dites aux autres apprenants ce que vous en avez pensé.';
$string['reviewinvalid'] = 'Choisissez une note de 1 à 5 et rédigez un court avis.';
$string['reviewcountone'] = '{$a} avis';
$string['reviewcount'] = '{$a} avis';
$string['courserating'] = 'Note du cours';
$string['ratingscount'] = '{$a} notes';
$string['instructorcourses'] = '{$a} cours';
$string['instructorstudents'] = '{$a} étudiants';
$string['fieldratingdist'] = 'Répartition des étoiles';
$string['fieldratingdistdesc'] = 'Pourcentages pour 5, 4, 3, 2 et 1 étoiles, séparés par des virgules. Par exemple : 67, 22, 0, 11, 0.';
$string['fieldreviewlist'] = 'Avis rédigés';
$string['fieldreviewlistdesc'] = 'Un avis par ligne, au format Nom|date|étoiles|texte. Les étoiles sont un nombre de 1 à 5.';
$string['courseinstructorsempty'] = 'Aucun formateur n\'est indiqué pour ce cours.';

$string['aboutnav'] = 'À propos';
$string['contactnav'] = 'Contact';
$string['faqnav'] = 'Questions fréquentes';
$string['contactsendfailed'] = 'Le message n\'a pas pu être envoyé. Réessayez plus tard, ou utilisez les coordonnées du pied de page.';

$string['aboutkicker'] = 'NextGen Academy';
$string['abouttitle'] = 'À propos de nous';
$string['aboutintro'] = 'Formation pratique à l\'entrepreneuriat en ligne pour les jeunes, les étudiants et les professionnels au Cameroun, en Afrique et à l\'international.';
$string['aboutsectioncount'] = '0';
$string['aboutcontactlabel'] = 'Nous contacter';
$string['aboutwhotitle'] = 'Qui nous sommes';
$string['aboutwhotext1'] = 'Next Gen Entrepreneurship Development Ltd est un centre de formation professionnelle dédié à la promotion des connaissances, des compétences et des opportunités entrepreneuriales auprès des jeunes, des étudiants et des professionnels au Cameroun, en Afrique et à l\'international. Cette formation professionnelle et technique à l\'entrepreneuriat en ligne est l\'un de ses projets.';
$string['aboutwhotext2'] = 'La formation rend les connaissances et les compétences entrepreneuriales accessibles et concrètes. En plus de {$a} cours pratiques, les apprenants reçoivent un accompagnement, un mentorat et des compétences techniques pour créer ou développer leur propre entreprise.';
$string['aboutwhotext2one'] = 'La formation rend les connaissances et les compétences entrepreneuriales accessibles et concrètes. En plus d\'un cours pratique, les apprenants reçoivent un accompagnement, un mentorat et des compétences techniques pour créer ou développer leur propre entreprise.';
$string['aboutwhotext2none'] = 'La formation rend les connaissances et les compétences entrepreneuriales accessibles et concrètes. Les apprenants reçoivent un accompagnement, un mentorat et des compétences techniques pour créer ou développer leur propre entreprise.';
$string['aboutteachtitle'] = 'Ce que nous enseignons';
$string['aboutteachtext'] = '{$a}, couvrant :';
$string['aboutareas'] = "Agro-entrepreneuriat\nTechpreneuriat\nEntrepreneuriat vert\nEntrepreneuriat culturel\nEntrepreneuriat sportif\nEntrepreneuriat enseignant\nEntrepreneuriat du Royaume\nEntrepreneuriat islamique\nEntrepreneuriat de guerre";
$string['aboutareasmore'] = 'Et de nombreux autres cours associés.';
$string['aboutgaintitle'] = 'Ce que vous allez acquérir';
$string['aboutoutcomestitle'] = 'À la fin de la formation, vous devriez :';
$string['aboutoutcome1'] = 'Acquérir des connaissances et des compétences dans différents types d\'entrepreneuriat.';
$string['aboutoutcome2'] = 'Être motivé à exploiter des opportunités d\'affaires dans différents secteurs et domaines.';
$string['aboutoutcome3'] = 'Acquérir une culture entrepreneuriale dans différents secteurs et domaines.';
$string['aboutvisiontitle'] = 'Notre vision';
$string['aboutvisionquote'] = 'Devenir le pôle numérique de référence en Afrique pour la transformation entrepreneuriale : un continent où chaque jeune, quel que soit son lieu ou son parcours, possède les compétences, la confiance et l\'opportunité de créer des entreprises qui portent une croissance économique durable, réduisent le chômage et libèrent tout le potentiel de la jeunesse africaine, au bénéfice du Cameroun, de l\'Afrique centrale et du monde.';
$string['aboutvisionlisttitle'] = 'L\'avenir vers lequel nous travaillons';
$string['aboutvisionpoints'] = "Chaque jeune Camerounais qui rêve de créer une entreprise a accès à la formation, au mentorat et à l\'accompagnement pour transformer ce rêve en réalité.\nL\'apprentissage en ligne est reconnu comme un puissant moteur d\'autonomisation économique, qui atteint les jeunes dans les villages les plus éloignés comme dans les villes les plus animées.\nL\'entrepreneuriat est une capacité pratique et vécue, pas seulement une matière scolaire : une génération qui voit les défis comme des opportunités et crée des solutions là où d\'autres ne voient que des problèmes.\nLe Cameroun devient un modèle pour l\'Afrique, en transformant son défi démographique en dividende démographique grâce à l\'énergie entrepreneuriale.\nLa jeunesse africaine n\'est pas vue comme un problème à résoudre, mais comme la solution aux défis les plus pressants du continent : création d\'emplois, innovation et développement durable.\nUne nouvelle génération d\'entrepreneurs africains bâtit des entreprises qui concurrencent à l\'échelle mondiale, créent des emplois localement et aident à résoudre certains des défis les plus complexes de l\'humanité.";
$string['aboutvisionclose'] = 'Cette vision est ambitieuse parce que le problème est grave. Nous ne visons pas un changement progressif. Nous visons une transformation.';
$string['aboutmissiontitle'] = 'Notre mission';
$string['aboutmissionquote'] = 'Donner aux jeunes Camerounais et Africains des compétences entrepreneuriales et numériques de niveau international, pratiques et adaptées au marché, grâce à une plateforme en ligne accessible, bilingue et abordable qui associe une formation structurée, le mentorat, le soutien d\'une communauté et des liens vers des opportunités, afin qu\'ils puissent créer leur propre avenir économique, générer de l\'emploi et porter un développement durable sur le continent.';
$string['aboutcommitmentstitle'] = 'Nos engagements';
$string['aboutcommitment1title'] = 'Accessibilité';
$string['aboutcommitment1text'] = 'Une formation qui rejoint les jeunes là où ils sont : en ville et dans les villages, en anglais et en français, sur téléphone et sur ordinateur, en ligne et hors ligne.';
$string['aboutcommitment2title'] = 'Pertinence';
$string['aboutcommitment2text'] = 'Un programme conçu avec des employeurs, des entrepreneurs et des experts du secteur, et mis à jour à mesure que l\'économie numérique change.';
$string['aboutcommitment3title'] = 'Caractère pratique';
$string['aboutcommitment3text'] = 'Des compétences que vous pouvez appliquer tout de suite : plan d\'affaires, culture financière, marketing numérique, gestion des opérations et adoption des technologies.';
$string['aboutcommitment4title'] = 'Accompagnement';
$string['aboutcommitment4text'] = 'Mentorat, communauté et suivi après la formation, à travers les défis de la création et de la croissance d\'une entreprise.';
$string['aboutcommitment5title'] = 'Accessibilité financière';
$string['aboutcommitment5text'] = 'Des programmes à faible coût ou gratuits, soutenus par des partenariats, du parrainage et un modèle économique durable.';
$string['aboutcommitment6title'] = 'Impact';
$string['aboutcommitment6text'] = 'Le succès se mesure aux entreprises lancées, aux emplois créés et aux vies transformées, pas aux certificats délivrés.';
$string['aboutcommitment7title'] = 'Excellence';
$string['aboutcommitment7text'] = 'Les plus hautes exigences de qualité des contenus, d\'expérience des apprenants et d\'intégrité de l\'institution.';
$string['aboutcommitment8title'] = 'Collaboration';
$string['aboutcommitment8text'] = 'Travailler avec l\'État, le secteur privé, la société civile et des partenaires internationaux pour amplifier notre impact.';
$string['aboutimpacttitle'] = 'Pourquoi ce travail compte';
$string['aboutimpactlead'] = 'Chaque partie de notre vision et de notre mission répond à un défi que les jeunes du Cameroun rencontrent aujourd\'hui.';
$string['aboutimpactchallenge'] = 'Le défi';
$string['aboutimpactresponse'] = 'Notre réponse';
$string['aboutimpacttagvision'] = 'Vision';
$string['aboutimpacttagmission'] = 'Mission';
$string['aboutimpact1challenge'] = '36 % des jeunes Camerounais sont au chômage.';
$string['aboutimpact1vision'] = 'Un continent où chaque jeune peut créer son propre avenir économique.';
$string['aboutimpact1mission'] = 'Donner aux jeunes des compétences entrepreneuriales pour créer leur propre emploi et devenir chefs d\'entreprise et investisseurs.';
$string['aboutimpact2challenge'] = 'Les principaux obstacles sont les lacunes en compétences et en formation.';
$string['aboutimpact2mission'] = 'Proposer une formation pratique, adaptée au marché, qui comble précisément ces lacunes.';
$string['aboutimpact3challenge'] = '60 % veulent créer une entreprise, mais n\'en ont pas les capacités.';
$string['aboutimpact3mission'] = 'Fournir les compétences, le mentorat et l\'accompagnement pour transformer l\'aspiration en action.';
$string['aboutimpact4challenge'] = '61 % envisagent l\'émigration.';
$string['aboutimpact4vision'] = 'Faire du fait de rester et de construire chez soi une option viable, attrayante et gratifiante.';
$string['aboutimpact5challenge'] = 'Le système éducatif forme des diplômés, mais pas des créateurs d\'emplois.';
$string['aboutimpact5mission'] = 'Construire l\'état d\'esprit et les capacités entrepreneuriales que l\'enseignement formel n\'apporte pas.';
$string['aboutimpact6challenge'] = 'L\'économie numérique crée de nouvelles opportunités, mais peu de personnes ont les compétences pour les saisir.';
$string['aboutimpact6mission'] = 'Développer la culture numérique et les compétences d\'adoption des technologies pour l\'économie d\'aujourd\'hui.';
$string['aboutimpact7challenge'] = 'La formation en présentiel est limitée par la géographie et le coût.';
$string['aboutimpact7mission'] = 'Une formation en ligne, accessible et abordable, qui rejoint les apprenants où qu\'ils soient.';
$string['aboutteamtitle'] = 'Notre équipe';
$string['aboutteamlink'] = 'Rencontrer l\'équipe';
$string['aboutfounderlabel'] = 'Fondateur';
$string['aboutfoundername'] = 'Professor Vukenkeng Andrew Wujung';
$string['aboutfoundertitle'] = 'Professeur d\'économie et d\'entrepreneuriat';
$string['aboutfounderhighlights'] = "Doctorat en sciences économiques, Université de Yaoundé II, Soa\nPlus de 30 ans dans l\'enseignement secondaire et supérieur\nPlus de 60 publications scientifiques, ouvrages et chapitres";
$string['aboutfounderbio1'] = 'Le professeur Wujung est un professeur camerounais d\'économie et d\'entrepreneuriat, fort d\'une longue expérience dans l\'enseignement supérieur, l\'enseignement, la recherche, l\'administration universitaire et le développement de l\'entrepreneuriat. Ses intérêts portent sur l\'économie du développement, l\'entrepreneuriat, la croissance et le développement économiques, ainsi que sur les enjeux environnementaux du développement.';
$string['aboutfounderbio2'] = 'Ses recherches couvrent l\'entrepreneuriat, la croissance économique, le développement, les questions environnementales, la pauvreté et la gouvernance. Des travaux récents portent sur l\'éducation à l\'entrepreneuriat, la viabilité financière des universités d\'État, la transformation numérique des PME, ainsi que l\'orientation entrepreneuriale et la performance des PME.';
$string['aboutfounderbeyondtitle'] = 'Au-delà de l\'université';
$string['aboutfounderbeyond'] = 'Il contribue à la formation à l\'entrepreneuriat, au renforcement des capacités, à la collaboration en recherche, au développement communautaire et au service professionnel. Il est conseiller économique de la Kedjom Agricultural Development Cooperative et président du conseil d\'administration de WAMVA Ventures Ltd.';
$string['aboutfounderpositionstitle'] = 'Fonctions exercées';
$string['aboutfounderpositions'] = "Conseiller technique du recteur, Université de Bertoua (depuis août 2025)\nInspecteur des services, Ministère de l\'Enseignement supérieur (MINESUP)\nDirecteur adjoint, Institut supérieur de commerce et de gestion\nChef du département d\'économie et chef de la division de la comptabilité, Université de Bamenda\nEnseignant-chercheur, Université de Buea";
$string['aboutparticipantstitle'] = 'Participants clés';
$string['aboutparticipants'] = "Professor Vukenkeng Andrew Wujung\nDr. Akumbom Paul\nDr. Kiambom Tracey\nDr. Forbe Hodu\nApostle Nfor Kilian\nNtsinu Raheal Viwin\nAnembom Francine\nBuemembom Favour Wujung\nAyeah Godlove Akoni\nWulneghewimbom Anguombom Cedrick";
$string['aboutparticipantsmore'] = 'Et d\'autres contributeurs qui accompagnent nos apprenants.';
$string['teamnav'] = 'Équipe';
$string['teamkicker'] = 'NextGen Academy';
$string['teamtitle'] = 'Notre équipe';
$string['teamintro'] = 'Les personnes qui dirigent, enseignent et construisent Next Gen Entrepreneurship Development Ltd.';
$string['teamsectioncount'] = '0';
$string['teammemberfavourname'] = 'Buemembom Favour Wujung';
$string['teammemberfavourrole'] = 'Expert en marketing et communication, stratège en marketing numérique et entrepreneur';
$string['teammemberfavourbio'] = 'Buemembom Favour Wujung est un professionnel passionné du marketing et de la communication, et un entrepreneur, porté par le désir d\'inspirer la croissance, de créer des opportunités et de contribuer au développement entrepreneurial de l\'Afrique. Par son intérêt pour le marketing, l\'innovation numérique et le développement des affaires, il incarne un état d\'esprit tourné vers l\'avenir et l\'engagement de transformer les idées en impact concret.' . "\n\n" .
    'Chez NextGen Entrepreneurship Development Ltd, Favour joue un rôle essentiel dans l\'orientation marketing de l\'entreprise, le renforcement de son identité de marque et l\'élargissement de sa présence numérique. Il supervise les campagnes sur les réseaux sociaux, contribue aux initiatives marketing et aide à faire connaître la vision de NextGen Academy aux publics du Cameroun, d\'Afrique et du monde. Parallèlement à son travail chez NextGen Academy, il est aussi membre du réseau Young African Leaders Initiative (YALI).';
$string['teammemberponmumaname'] = 'Ponmuma Gabin Merveille Petit';
$string['teammemberponmumarole'] = 'Professionnel de la comptabilité, formateur et entrepreneur';
$string['teammemberponmumabio'] = 'Ponmuma Gabin Merveille Petit est un professionnel de la comptabilité, formateur, rédacteur de projets et jeune entrepreneur. Il est coordinateur de programme à l\'ESRC-Cameroon, responsable de la School of Business & Finance à PHIPT, et directeur de la School of Business and Management Sciences au CIUIC. Il est passionné par l\'éducation, la recherche, l\'entrepreneuriat et l\'aide aux étudiants pour développer des compétences pratiques. Il est travailleur, actif, bien organisé, et capable de travailler sous pression tout en gérant efficacement plusieurs responsabilités.';
$string['teammembermbalaname'] = 'Mbala Axel';
$string['teammembermbalarole'] = 'Juriste et archiviste';
$string['teammembermbalabio'] = 'Juriste (Master II droit privé) et archiviste cadre contractuel dans l\'administration publique depuis plus de douze ans. Formé en rédaction administrative, techniques archivistiques et leadership. Français-anglais.';
$string['teammemberayeahname'] = 'Ayeah Godlove Akoni';
$string['teammemberayeahrole'] = 'Ingénieur systèmes informatiques et logiciel';
$string['teammemberayeahbio'] = 'M. Ayeah Godlove Akoni est un professionnel camerounais de l\'informatique, formé en maintenance des réseaux et systèmes informatiques, avec une expérience pratique en ingénierie logicielle, support des systèmes informatiques, gestion d\'infrastructure, technologies web, administration de bases de données et résolution de problèmes techniques.' . "\n\n" .
    'Il associe des compétences de développement logiciel à une compréhension des systèmes, réseaux, serveurs et bases de données dont dépendent les applications numériques. Son parcours lui permet de contribuer au développement et à la maintenance de solutions logicielles, tout en assurant la configuration, le déploiement, le dépannage et le fonctionnement fiable des environnements informatiques.' . "\n\n" .
    'Son expérience en ingénierie logicielle comprend le développement d\'applications web, de services backend, d\'API REST, de plateformes pilotées par des bases de données et de systèmes d\'information métier. Il a travaillé avec des technologies telles que JavaScript, TypeScript, Node.js, Express, PHP, Laravel, Python, C# et .NET, ainsi qu\'avec des frameworks d\'interface comme React et Next.js. Son expérience des bases de données comprend MySQL, PostgreSQL et MongoDB.' . "\n\n" .
    'Ses compétences liées à l\'infrastructure comprennent les environnements Linux et Windows, le déploiement et la maintenance de serveurs, la configuration de serveurs web, le DNS, les certificats SSL/TLS, l\'administration distante, l\'hébergement d\'applications, le contrôle d\'accès, les sauvegardes et le dépannage. Sa formation en réseaux et en maintenance des systèmes soutient aussi son travail en dépannage de connectivité, maintenance matérielle et logicielle, systèmes d\'exploitation, configuration réseau et support technique des utilisateurs.' . "\n\n" .
    'En plus de cette expérience de mise en œuvre, M. Akoni a travaillé dans le tutorat et le mentorat, en aidant des apprenants à comprendre le développement web, la programmation backend, les bases de données, les API et les pratiques de développement logiciel. Cette expérience renforce sa capacité à expliquer des notions techniques, à accompagner les utilisateurs, à clarifier des solutions et à travailler en collaboration.' . "\n\n" .
    'Il est en mesure de contribuer à des organisations qui ont besoin d\'un professionnel polyvalent, capable d\'intervenir dans le développement logiciel, l\'exploitation informatique, le support des systèmes, les réseaux et l\'infrastructure numérique. L\'association de sa formation et de son expérience pratique est particulièrement utile aux services informatiques, aux prestataires de services technologiques, aux environnements informatiques d\'entreprise et aux organisations de télécommunications.';
$string['teammemberetamename'] = 'Etame Vivien Millan';
$string['teammemberetamerole'] = 'Technico-commercial';
$string['teammemberetamebio'] = 'Technico-commercial spécialisé en assistance commerciale digitale, fort de plus de huit ans d\'expérience dans la vente/closing en ligne, le suivi client (online et offline) et en campagnes publicitaires digitales.' . "\n\n" .
    'Français-Anglais.';
$string['teammemberderickname'] = 'Dr. Derick Yong';
$string['teammemberderickrole'] = 'Communication, narration et arts créatifs';
$string['teammemberderickbio'] = 'Le Dr Derick Yong est titulaire d\'un doctorat en littérature anglaise, spécialisé dans l\'adaptation du roman au film. Son travail couvre l\'anglais professionnel, la communication, l\'écriture créative, le scénario, la production cinématographique, le théâtre et la performance. Il allie recherche universitaire et pratique du cinéma, et forme à la communication, à la narration, au scénario et aux arts créatifs.';
$string['teammembernayahname'] = 'Dr. Nayah Gazelle Abang';
$string['teammembernayahrole'] = 'Maître de conférences, formatrice et entrepreneure sociale';
$string['teammembernayahbio'] = 'La docteure Nayah Gazelle Abang est maître de conférences en administration publique et en politiques publiques à l\'Université de Buea, au Cameroun, et enseignante invitée à l\'Assam Don Bosco University, en Inde. Elle est fondatrice de la Voice of Hope Foundation (VOHOF) et ambassadrice du Cameroun pour la Journée mondiale de la prise de parole.' . "\n\n" .
    'Elle transmet des connaissances et des compétences pratiques en entrepreneuriat, politiques publiques, prise de parole en public et leadership communautaire.';
$string['teammemberpetername'] = 'Dr. Peter Abuengmoh';
$string['teammemberpeterrole'] = 'Entrepreneur, technologue alimentaire et rédacteur de subventions';
$string['teammemberpeterbio'] = 'Le Dr Peter Abuengmoh est entrepreneur, technologue alimentaire et rédacteur de subventions. Il est fondateur principal de LEPEGDEMT FOODS Ltd, un groupe d\'initiative commune à Bamenda, où il associe la transformation alimentaire à la croissance de l\'entreprise et à l\'impact communautaire.' . "\n\n" .
    'Il a obtenu plus de quatre subventions pour lui-même et son entreprise. Il a aussi guidé et accompagné neuf jeunes femmes jusqu\'à l\'obtention de la subvention entrepreneuriale de 5 000 $ de la Tony Elumelu Foundation, ouvrant des portes aux entreprises dirigées par des femmes.' . "\n\n" .
    'Il dirige la School of Home Economics, Bakery and Food Processing Technology au Prestige Higher Institute of Professional Training, à Bamenda, où il forme et accompagne la prochaine génération de professionnels de l\'alimentation.';
$string['teammemberforbename'] = 'Dr. Forbe Hodu';
$string['teammemberforberole'] = 'Sciences organisationnelles';
$string['teammemberforbebio'] = 'Le Dr Forbe Hodu est expert en sciences organisationnelles.';
$string['teammemberfrancinename'] = 'Anembom Francine';
$string['teammemberfrancinerole'] = 'Entrepreneure de mode et gestionnaire de projets';
$string['teammemberfrancinebio'] = 'Anembom Francine est entrepreneure dans la mode et titulaire d\'un MBA en gestion de projets. Elle aborde le développement des affaires avec responsabilité, intégrité, travail d\'équipe et une volonté de progresser en continu. Sa devise est l\'engagement envers l\'excellence, la croissance et les résultats.' . "\n\n" .
    'Son travail couvre le développement des affaires et l\'entrepreneuriat, la planification et la gestion de projets, la communication et le travail d\'équipe, l\'organisation et l\'administration, le leadership et la résolution de problèmes. Elle s\'intéresse au développement organisationnel, à une gestion efficace et à l\'atteinte des objectifs de l\'organisation.';
$string['teammemberkiambomname'] = 'Dr. Kiambom Tracey';
$string['teammemberkiambomrole'] = 'Consultante senior et chercheuse en agriculture';
$string['teammemberkiambombio'] = 'La docteure Kiambom Tracey est consultante senior et chercheuse en agriculture à Yaoundé, spécialisée en production animale et en biotechnologie. Scientifique agricole primée, elle a plus de cinq ans d\'expérience en agriculture, moyens de subsistance et sécurité alimentaire, au service des populations exposées à l\'insécurité alimentaire.' . "\n\n" .
    'Elle maintient un haut niveau d\'exigence, analyse les difficultés de fonctionnement et propose des solutions durables.';
$string['teammemberemmanuelname'] = 'Emmanuel Mbunwe Wepngong';
$string['teammemberemmanuelrole'] = 'Formateur d\'enseignants';
$string['teammemberemmanuelbio'] = 'Emmanuel Mbunwe Wepngong est un formateur d\'enseignants qui s\'appuie sur les faits, avec 15 ans de conception et d\'animation de formations professionnelles, de la petite enfance au primaire, au secondaire et au lycée. Il est titulaire d\'un master en qualité de l\'éducation pour les pays en développement de l\'Otto Friedrich Universität Bamberg, en Allemagne.' . "\n\n" .
    'Il a enseigné l\'histoire et l\'économie de 1998 à 2009 et forme des enseignants depuis 2009. Son travail comprend des ateliers sur l\'enseignement, l\'apprentissage et les critères de qualité de l\'enseignement, des supports de formation pour les enseignants à tous les niveaux, des suivis en classe et des observations de leçons avec des retours visant à améliorer l\'efficacité des enseignants. Il a été chercheur principal 2 du projet du Centre de recherches pour le développement international sur l\'enseignement des STEM et la formation des élèves du primaire (IDRC-STEM/STEPS) au Cameroun, et panéliste et facilitateur aux conférences IMPEQ au Cameroun et au Rwanda.' . "\n\n" .
    'Il aide les enseignants à atteindre leur potentiel et à créer des expériences d\'apprentissage solides. Ses points forts sont l\'organisation, l\'adaptabilité, la communication, la résolution de problèmes et le leadership.';
$string['teammemberndzembantehname'] = 'Dr. Ndzembanteh Aboubakary Nulambeh';
$string['teammemberndzembantehrole'] = 'Chercheur et maître de conférences';
$string['teammemberndzembantehbio'] = 'Le Dr Ndzembanteh Aboubakary Nulambeh est chercheur et maître de conférences à l\'Université de Bamenda, avec plus de huit ans d\'enseignement, de recherche appliquée et de travail universitaire. Il est titulaire d\'un doctorat en économie internationale de la Bursa Uludag University, en Türkiye.' . "\n\n" .
    'Ses travaux portent sur l\'économie de l\'environnement, l\'économie et la finance islamiques, l\'économétrie appliquée, la gestion de la chaîne d\'approvisionnement et la logistique. Il est l\'auteur de plus de 20 publications, citées plus de 140 fois sur Google Scholar, sur la durabilité, le développement économique et les politiques fondées sur les données. À côté de l\'enseignement et de l\'encadrement de troisième cycle, il participe à la recherche interdisciplinaire, à la collaboration universitaire internationale et à des initiatives de durabilité. Son intérêt porte sur des solutions pratiques, fondées sur la recherche, pour le développement durable, des politiques publiques efficaces et une croissance économique inclusive en Afrique subsaharienne.';
$string['teammemberakumbomname'] = 'Dr. Akumbom Paul';
$string['teammemberakumbomrole'] = 'Économiste du développement et agro-entrepreneur';
$string['teammemberakumbombio'] = 'Le Dr Akumbom Paul est un économiste du développement et agro-entrepreneur camerounais, titulaire d\'un doctorat en gestion de projets agroalimentaires de l\'Université de Bamenda. Il est enseignant au Higher Institute of Commerce and Management (HICM) de l\'Université de Bamenda, spécialisé en entrepreneuriat, développement de l\'agrobusiness, gestion de projets, et méthodes de recherche quantitatives et qualitatives.';
$string['teammembersigiminame'] = 'Dr. Sigimi Fidelis';
$string['teammembersigimirole'] = 'Enseignant en marketing';
$string['teammembersigimibio'] = 'Le Dr Sigimi Fidelis est enseignant au département de marketing du Higher Institute of Commerce and Management. Son domaine de recherche et sa spécialité sont le comportement du consommateur et la stratégie marketing.';
$string['teammemberawemuname'] = 'Dr. Awemu Jonah';
$string['teammemberawemurole'] = 'Économiste, enseignant et chercheur';
$string['teammemberawemubio'] = 'Awemu Jonah Wayih (PhD) est économiste, enseignant et chercheur. Il a une solide formation universitaire et des compétences professionnelles variées en enseignement, recherche, écriture créative, et technologies de l\'information et de la communication.' . "\n\n" .
    'Sur le plan académique, il est titulaire d\'un doctorat en économie (2025) et d\'un master (M.Sc.) en économie (2017), tous deux de l\'Université de Bamenda, au Cameroun. Il a également obtenu une licence (B.Sc.) en économie (2009) à l\'Université de Dschang et un DIPES I en économie (2005) à l\'annexe de l\'École normale supérieure de Bambili.' . "\n\n" .
    'Ses études secondaires se sont conclues par le certificat du GCE Advanced Level au Government Bilingual High School Bamenda en 2001 et le certificat du GCE Ordinary Level au Cameroon College of Arts, Science and Technology (CCAST), à Bambili, en 1999.' . "\n\n" .
    'Ses recherches portent sur la croissance économique, les politiques publiques, les énergies renouvelables, la complexité économique et le développement durable. Il a contribué à des publications scientifiques, notamment Government Spending and Economic Growth, Keynes or Wagner for Cameroon? ARDL/T-Y-VAR Approach (2022), qui examine le lien entre les dépenses publiques et la croissance économique au Cameroun, et Implications of Economic Complexity for Renewable Energy Consumption and Sustainable Development: Evidence from Sub-Saharan Africa (2024). Cette dernière a établi la contribution significative de la consommation d\'énergies renouvelables au développement durable et le rôle médiateur de la complexité économique.' . "\n\n" .
    'Au-delà de la recherche, il possède de solides compétences en enseignement, en écriture créative, en TIC, en analyse logique et en gestion quantitative. Ses aptitudes pluridisciplinaires traduisent un engagement pour le développement intellectuel, la créativité et la transmission du savoir.' . "\n\n" .
    'Il est passionné par l\'acquisition, le partage et l\'application pratique des connaissances, les jeux éducatifs, l\'humour, l\'apprentissage et les visites. Il maîtrise l\'anglais et a une bonne connaissance du français.' . "\n\n" .
    'Son parcours universitaire, ses contributions à la recherche et ses compétences professionnelles traduisent un engagement durable pour l\'excellence éducative, le progrès intellectuel, la créativité et l\'application du savoir au développement de la société de manière durable.';
$string['teammemberabetyname'] = 'Dr. Abety Paul';
$string['teammemberabetyrole'] = 'Économiste du développement et inspecteur pédagogique national';
$string['teammemberabetybio'] = 'Le Dr Abety Paul est titulaire d\'un doctorat en économie (économie du développement) obtenu à l\'Université de Bamenda (UBa), au Cameroun. Il est titulaire d\'une licence en économie de l\'Université de Dschang, au Cameroun, et d\'un master en économie (économie du travail) de l\'UBa. Il est enseignant formé en sciences et techniques économiques (EST), titulaire du diplôme de professeur de l\'enseignement secondaire technique (DIPET 2). Il est actuellement inspecteur pédagogique national au ministère des Enseignements secondaires du Cameroun, après six années comme inspecteur pédagogique régional du Nord-Ouest. Il encadre les enseignants d\'entrepreneuriat, révise et enregistre leurs leçons pour diffusion sur le Centre d\'apprentissage à distance par l\'IA du ministère (AIDLC). Il est également examinateur du GCE Advanced Level en économie depuis 2009.';
$string['aboutvisittitle'] = 'Nous contacter';
$string['aboutcalllabel'] = 'Téléphone';
$string['aboutemaillabel'] = 'Courriel';
$string['aboutctatitle'] = 'Prêt à commencer ?';
$string['aboutctatext'] = 'Choisissez un cours et commencez à apprendre, ou contactez-nous si vous avez une question.';
$string['aboutctalabel'] = 'Parcourir les cours';
$string['aboutcreateaccount'] = 'Créer un compte';
$string['officelabel'] = 'Bureau';
$string['officedirections'] = 'Obtenir l\'itinéraire';

$string['contactkicker'] = 'NextGen Academy';
$string['contacttitle'] = 'Nous contacter';
$string['contactintro'] = 'Une question sur un cours ou sur votre compte ? Envoyez-nous un message, appelez-nous ou venez dans l\'un de nos bureaux.';
$string['contactsectioncount'] = '1';
$string['contactsection1title'] = 'Quoi indiquer';
$string['contactsection1text'] = 'Nommez le cours si la question en concerne un, et dites ce que vous avez déjà essayé. Les visiteurs doivent utiliser une adresse de courriel qu\'ils peuvent ouvrir.';
$string['contactformtitle'] = 'Envoyer un message';
$string['contactformlead'] = 'Remplissez les informations ci-dessous. Les champs obligatoires sont indiqués.';
$string['contactsubmit'] = 'Envoyer le message';
$string['contactfieldname'] = 'Nom';
$string['contactfieldemail'] = 'Adresse de courriel';
$string['contactfieldsubject'] = 'Objet';
$string['contactfieldmessage'] = 'Message';
$string['contactnameplaceholder'] = 'Votre nom complet';
$string['contactemailplaceholder'] = 'vous@exemple.com';
$string['contactsubjectplaceholder'] = 'Comment pouvons-nous vous aider ?';
$string['contactmessageplaceholder'] = 'Indiquez le nom du cours, ce que vous avez essayé et ce dont vous avez besoin ensuite.';
$string['contactchannels'] = 'Coordonnées';
$string['contactphonelabel'] = 'Téléphone';
$string['contactemaillabel'] = 'Courriel';
$string['contactresponse'] = 'Nous visons à répondre dans un délai d\'un à deux jours ouvrables.';

$string['privacykicker'] = 'Politiques';
$string['privacytitle'] = 'Politique de confidentialité';
$string['privacyintro'] = 'Cette page explique ce que ce site conserve à votre sujet pour que les cours, la connexion et l\'assistance puissent fonctionner.';
$string['privacysectioncount'] = '4';
$string['privacysection1title'] = 'Compte et traces d\'apprentissage';
$string['privacysection1text'] = 'Lorsque vous créez un compte, le site enregistre le nom, l\'adresse de courriel et les informations de connexion dont Moodle a besoin. L\'inscription aux cours, l\'achèvement des activités, les notes et les messages de forum restent liés à ce compte.';
$string['privacysection2title'] = 'Messages de contact';
$string['privacysection2text'] = 'Le formulaire de contact envoie votre nom, votre adresse de courriel et votre message à l\'utilisateur d\'assistance du site. Le thème n\'en conserve pas sa propre copie.';
$string['privacysection3title'] = 'Cookies et session';
$string['privacysection3text'] = 'Un cookie de session vous maintient connecté lorsque vous passez d\'une page à l\'autre. Moodle peut aussi enregistrer la langue et les préférences de l\'éditeur dans votre compte.';
$string['privacysection4title'] = 'Demander des informations vous concernant';
$string['privacysection4text'] = 'Les personnes connectées peuvent consulter les outils de confidentialité de Moodle depuis leur profil. Les autres peuvent utiliser la page Contact et demander ce que le site détient pour leur adresse de courriel.';

$string['termskicker'] = 'Politiques';
$string['termstitle'] = 'Conditions d\'utilisation';
$string['termsintro'] = 'Ces conditions couvrent l\'utilisation de ce site d\'apprentissage, de ses cours et des comptes qui servent à les rejoindre.';
$string['termssectioncount'] = '5';
$string['termssection1title'] = 'Utilisation du site';
$string['termssection1text'] = 'Vous pouvez parcourir le catalogue public et utiliser les cours auxquels vous êtes inscrit. N\'essayez pas d\'ouvrir le compte d\'une autre personne, ni de perturber le site.';
$string['termssection2title'] = 'Comptes';
$string['termssection2text'] = 'Gardez votre mot de passe confidentiel. L\'activité réalisée pendant que vous êtes connecté est enregistrée sur votre compte. Prévenez l\'assistance du site si vous perdez l\'accès.';
$string['termssection3title'] = 'Contenus de cours';
$string['termssection3text'] = 'Les leçons, les fichiers et les devoirs appartiennent aux personnes qui ont publié le cours, sauf indication contraire sur la page du cours. Ne les copiez pas vers un autre site sans autorisation.';
$string['termssection4title'] = 'Modifications';
$string['termssection4text'] = 'Les cours peuvent être mis à jour, masqués ou fermés par les personnes qui les enseignent. Ces conditions peuvent être remplacées par une version plus récente de cette page.';
$string['termssection5title'] = 'Remboursements';
$string['termssection5text'] = 'Des frais paient l\'inscription au cours nommé lors du paiement. Demandez un remboursement avant de commencer les activités du cours. Utilisez la page de contact et l\'adresse courriel de votre compte, et nommez le cours. Une fois des activités terminées dans le cours, les frais ne sont pas remboursés. Une place sponsorisée ou un programme sur mesure suit l\'accord conclu avant le début du groupe.';

$string['faqkicker'] = 'Aide';
$string['faqtitle'] = 'Questions fréquentes';
$string['faqintro'] = 'Réponses courtes sur NextGen Academy, nos cours, et la façon de les rejoindre puis d\'y revenir.';
$string['faqsectioncount'] = '9';
$string['faqsection1title'] = 'Qu\'est-ce que NextGen Academy ?';
$string['faqsection1text'] = 'NextGen Academy est la formation professionnelle et technique à l\'entrepreneuriat en ligne de Next Gen Entrepreneurship Development Ltd, un centre de formation professionnelle qui promeut les connaissances, les compétences et les opportunités entrepreneuriales au Cameroun, en Afrique et à l\'international.';
$string['faqsection2title'] = 'À qui s\'adressent les cours ?';
$string['faqsection2text'] = 'Aux jeunes, aux étudiants et aux professionnels qui veulent créer ou développer une entreprise, où qu\'ils soient.';
$string['faqsection3title'] = 'Que couvrent les cours ?';
$string['faqsection3text'] = '{$a}, notamment l\'agro-entrepreneuriat, le techpreneuriat, l\'entrepreneuriat vert, culturel et sportif, l\'entrepreneuriat enseignant, ainsi que l\'entrepreneuriat du Royaume, islamique et de guerre.';
$string['faqsection4title'] = 'Où sont vos bureaux ?';
$string['faqsection4text'] = 'Notre siège est à Yaoundé et notre agence est à Bertoua. Les adresses, les itinéraires et notre numéro de téléphone se trouvent sur les pages À propos et Contact.';
$string['faqsection5title'] = 'Comment trouver un cours ?';
$string['faqsection5text'] = 'Ouvrez Cours dans l\'en-tête, ou parcourez par catégorie. Seuls les cours publiés sont listés.';
$string['faqsection6title'] = 'Comment rejoindre un cours ?';
$string['faqsection6text'] = 'Ouvrez le cours et utilisez la méthode d\'inscription indiquée. Certains cours sont ouverts. D\'autres demandent une clé ou l\'inscription par un formateur.';
$string['faqsection7title'] = 'Où ma progression est-elle enregistrée ?';
$string['faqsection7text'] = 'Connectez-vous, puis ouvrez Mes cours ou le cours lui-même. L\'achèvement reste lié à votre compte, donc une visite ultérieure montre ce qui est déjà fait.';
$string['faqsection8title'] = 'J\'ai oublié mon mot de passe.';
$string['faqsection8text'] = 'Utilisez Mot de passe oublié sur la page de connexion et saisissez l\'adresse de courriel du compte. Nous vous enverrons un lien pour le réinitialiser.';
$string['faqsection9title'] = 'Qui contacter pour obtenir de l\'aide ?';
$string['faqsection9text'] = 'Utilisez Contact dans l\'en-tête, ou appelez-nous au numéro indiqué dans le pied de page. Indiquez le nom du cours si la question en concerne un.';

$string['institutionheading'] = 'Programmes institutionnels';
$string['institutionintro'] = 'Des cours pour une école, une entreprise ou un groupe sponsorisé. Les cours, la taille du groupe et les frais sont confirmés avant toute inscription. Un cours seul garde Demander un devis sur sa propre page.';
$string['institution1title'] = 'Forfaits institutionnels';
$string['institution1text'] = 'Choisissez plusieurs cours publiés pour le personnel ou les étudiants d\'une organisation.';
$string['institution2title'] = 'Cohortes sponsorisées';
$string['institution2text'] = 'Un sponsor peut prendre en charge les frais d\'un groupe nommé. Ces apprenants rejoignent le groupe plutôt que de payer chacun de leur côté.';
$string['institution3title'] = 'Programmes sur mesure';
$string['institution3text'] = 'Un programme peut suivre les cours publiés, ou être organisé autour du travail d\'une organisation.';
$string['institutioncta'] = 'Demander un devis';
$string['facultyprev'] = 'Formateur précédent';
$string['facultynext'] = 'Formateur suivant';

$string['dashboardhello'] = 'Bonjour, {$a}';
$string['dashboardtoday'] = 'Nous sommes le {$a}.';
$string['dashboardlastlogin'] = 'Votre dernière connexion remonte à {$a}.';
$string['dashboardrecent'] = 'Cours consulté récemment :';
$string['dashboardcontinue'] = 'Continuer à apprendre';
$string['dashboardlessonin'] = 'Dans {$a}';
$string['dashboardready'] = 'Ouvrez un cours lorsque vous êtes prêt à continuer.';
$string['dashboardmycourses'] = 'Mes cours';
$string['dashboardall'] = 'Tous les cours';
$string['dashboardinprogress'] = 'En cours';
$string['dashboardpast'] = 'Passés';
$string['dashboardnoprogress'] = 'Aucun critère d\'achèvement';

$string['blognav'] = 'Blog';
$string['coursesnav'] = 'Cours';
$string['categoriesnav'] = 'Catégories';
$string['explorecourses'] = 'Explorer les cours';
$string['learnmore'] = 'En savoir plus';
$string['mylearning'] = 'Mon apprentissage';
$string['officemain'] = 'Siège';
$string['officebranch'] = 'Agence';
$string['blogintro'] = 'Notes et actualités de NextGen Academy.';
$string['blogback'] = 'Tous les billets';
$string['blogedited'] = 'Mis à jour {$a}';
$string['blogsignin'] = 'Connectez-vous pour commenter';
$string['blogcontinue'] = 'Continuer la lecture';
$string['blogwatchyoutube'] = 'Regarder sur YouTube';
$string['blogsharex'] = 'Partager sur X';
$string['blogsharefacebook'] = 'Partager sur Facebook';
$string['blogsharelinkedin'] = 'Partager sur LinkedIn';
$string['blogshareemail'] = 'Partager par courriel';
$string['blogcopylink'] = 'Copier le lien';
$string['blogcopied'] = 'Lien copié';
$string['emailfooter'] = 'Ce message a été envoyé par';
