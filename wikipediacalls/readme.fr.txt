Marques simples pour Wikipedia. Le filtre exploite la langue courante 
pour rediriger vers le Wikipedia dans la langue de la session courante.

Auteur : Val�ry Fr�maux. 11/2006 (vf@eisti.fr)

Pour l'installer:
    - copier dans <%%moodle_install%%>/filter
    - copier les fichiers de langues n�cessaires aux emplacements 
      ad�quats dans <%%moodle_install%%>/lang
    - Activez le filtre depuis "Administration/Filtres".
  
Pour l'utiliser :
    - Balisage direct : 
    	
    	Marquer un mot par la balise [WP] provoque la cr�ation du lien 
    	direct vers Wikipedia pour le mot. Exemple :
    	
    	Ethnom�thodologie[WP]
    	
    	Pour marquer un groupe de mots ou une locution, il faut placer des 
    	espaces ins�cables (Ctrl+Maj+Esp dans la plupart des cas) entre les 
    	mots de la locution que pr�c�de le marqueur. Exemple :
    	
    	Yoshua[^s]Bar-Hillel[WP]
    	
    - Balisage indirect
    
      Pour atteindre un article diff�rent du mot marqu�, il suffit d'�tendre 
      la balise [WP] par un param�tre compl�mentaire. Le s�parateur est le | 
      (pipe). Exemple :
      
      Ethnologique[WP|Ethnologie]
      
    - Changement de la langue
    
      On peut accessoirement mentionner un troisi�me param�tre permettant 
      d'atteindre des articles dans une langue autre que celle de la session
      courante. Exemple :

      Ethnologique[WP|Ideology|en]

Param�trage :

	   Le filtre permet d'activer ou de d�sactiver le compte rendu des clefs
	   collect�es. Si elle est activ�e, la liste des liens Wikipedia est mentionn�e
	   en compte-rendu de bloc de contenu. Un lien est pr�sent� pour tester ces
	   liens. Dans tous les cas, seuls les professeurs du cours peuvent consulter
	   ce rapport et activer le test

Fonctions suppl�mentaires :

	 - Test automatique des liaisons
	 
	 Pour faciliter la v�rification des liaisons, une fonction automatique de
	 test des liens g�n�r�s a �t� impl�ment�e. Cette fonction permet, dans un 
	 bloc de contenu donn�, de tester la pr�sence de pages Wikipedia pour les
	 clefs d'articles g�n�r�s. La liste des clefs collect�es (rapport) offre un
	 lien vers une popup de test. 
	 
	 Clickez sur "D�marrer le test" pour lancer la s�quence de test des liaisons.
	 
	 Attention : Ce test fonctionne � partir du client (impl�ment� en Ajax). Vous
	 devez pouvoir charger des contenus multi-domaines pour pourvoir utiliser cette 
	 fonction. Cette possibilit� est r�glable dans les options de s�curit� du 
	 navigateur (IE -> Outils -> Options Internet -> S�curit� -> Personnaliser le
	 Niveau -> Acc�s aux sources de donn�es sur plusieurs domaines).
	 
	 Il est pr�f�rable de mettre cette option sur "Demander".

