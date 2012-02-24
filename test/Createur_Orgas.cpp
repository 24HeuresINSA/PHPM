#include <iostream>
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <fstream>
#include <string>
#include <sstream>
#include <vector>

using namespace std;
string intToString (int unentier);
string generer_ligne_orga(int id, string unNom, string unPrenom);
string obtenir_nom();
string generer_ligne_dispo(int iddispo,int idorga, vector<string>& Disponibilites ,int numCreneau);
int random(int i);
const int NB_ORGAS = 50;
const int Id_Debut_Orga = 12;
const int Id_Debut_Dispo = 17;

ifstream input("dico_noms.txt");

int main()
{
  /* initialize random seed: */
  srand ( time(NULL) );
  string tmp;
  int idCreationOrga = Id_Debut_Orga;
  
  vector<string> Disponibilites;
  Disponibilites.push_back("'2011-01-01 00:00:00', '2011-01-01 10:00:00'");
  Disponibilites.push_back("'2011-01-01 16:00:00', '2011-01-01 20:00:00'");
  Disponibilites.push_back("'2011-01-02 10:00:00', '2011-01-02 15:00:00'");
  Disponibilites.push_back("'2011-01-02 16:00:00', '2011-01-02 20:00:00'");
  Disponibilites.push_back("'2011-01-02 7:00:00', '2011-01-02 10:00:00'");



  
  for(int j=0; j<3 ; j++)
  input>>tmp;
  
  cout << "INSERT INTO `Orga` (`id`, `confiance_id`, `importid`, `nom`, `prenom`, `surnom`, `telephone`, `email`, `dateDeNaissance`, `departement`, `commentaire`, `permis`, `statut`, `is_admin`) VALUES"<<endl;
  
  for (int i=0;i<NB_ORGAS-1;i++)
  {cout << generer_ligne_orga(idCreationOrga,obtenir_nom(),obtenir_nom()) +", "<< endl;   
  idCreationOrga++;
  }
  cout << generer_ligne_orga(idCreationOrga,obtenir_nom(),obtenir_nom()) +" ;"<< endl; 

  
  idCreationOrga = Id_Debut_Orga;
  int iddispo = Id_Debut_Dispo;
  cout << "INSERT IGNORE INTO `Disponibilite` (`id`, `orga_id`, `debut`, `fin`) VALUES"<<endl;
  for (int i=0;i<NB_ORGAS-1;i++)
  {
      int num1 = rand() % Disponibilites.size() ;
      int num2 = rand() % Disponibilites.size() ;
    cout << generer_ligne_dispo(iddispo,idCreationOrga,Disponibilites,num1) +", "<< endl;  
      iddispo++;

          if (num1 != num2)
	  {     cout << generer_ligne_dispo(iddispo,idCreationOrga,Disponibilites,num2) +", "<< endl;  
	      iddispo++;
	  }
	    idCreationOrga++;

  }
        int num1 = rand() % Disponibilites.size() ;
  cout << generer_ligne_dispo(iddispo,idCreationOrga,Disponibilites,num1) +" ;" << endl; 
    iddispo++;
  idCreationOrga++;
  
  
  return 0;
}
  
 
string obtenir_nom()
{
  string tmp;
  string nom;
  
  input>>tmp;
  input>>nom;
  input>>tmp;
  
  return nom;
}

string generer_ligne_orga(int id, string unNom, string unPrenom)
{
  string ligne;
  ligne+="(";
  ligne+=intToString(id);
  ligne+=", ";
  ligne+=intToString(2+random(1));//confiance
  ligne+=", ";
  ligne+="NULL";
  ligne+=", ";
  ligne+="' "+unNom+" '";
  ligne+=", ";
  ligne+="' "+unPrenom+" '";
  ligne+=", ";
  ligne+="' "+string("Pseudo")+string(" '");
  ligne+=", ";
  ligne+="' "+string("06789654")+intToString (id)+string(" '");
  ligne+=", ";
  ligne+="'"+unNom+"."+unPrenom+"@insa-lyon.fr"+"'";
  ligne+=", ";
  ligne+="' "+string(intToString(1970+random(30))+"-02-15")+" '";
  ligne+=", ";
  ligne+="' "+intToString(random(90))+string(" '");
  ligne+=", ";
  ligne+="NULL";
  ligne+=", ";
  ligne+=intToString(random(2)); //permis
  ligne+=", ";
  ligne+="1"; // statut
  ligne+=", ";
  ligne+="0 "; // admin
  ligne+=") \n";
  
  return ligne;
  
}

string intToString (int unentier)
{
      // créer un flux de sortie
    ostringstream oss;
    // écrire un nombre dans le flux
    oss << unentier;
    // récupérer une chaîne de caractères
    string result = oss.str();
    
    return result;
  
}

string generer_ligne_dispo(int iddispo,int idorga, vector<string>& Disponibilites ,int numCreneau)
{
    string ligne;
  ligne+="(";
  ligne+=intToString(iddispo);
  ligne+=", ";
  ligne+=intToString(idorga);
  ligne+=", ";
  ligne+=Disponibilites[numCreneau];
  ligne+=") \n";
  
  return ligne;
  
}


int random(int i)
{
  int num = rand() % (i+1);
  return num;
}
  