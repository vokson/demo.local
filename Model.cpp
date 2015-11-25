#include "stdafx.h"
#include "Model.h"
Model::Model() {
// +++ NODES +++
this->nodes.push_back(Node(1, "", 0, 0, 0));
this->nodes.push_back(Node(2, "", 0, 0, 1));
this->nodes.push_back(Node(3, "", 1, 0, 0));
this->nodes.push_back(Node(4, "", 1, 0, 1));
this->nodes.push_back(Node(5, "", 2, 0, 0));
this->nodes.push_back(Node(6, "", 2, 0, 1));
this->nodes.push_back(Node(7, "", 3, 0, 0));
this->nodes.push_back(Node(8, "", 3, 0, 1));
this->nodes.push_back(Node(9, "", 4, 0, 0));
this->nodes.push_back(Node(10, "", 4, 0, 1));
this->nodes.push_back(Node(11, "", 5, 0, 0));
this->nodes.push_back(Node(12, "", 5, 0, 1));
this->nodes.push_back(Node(13, "", 6, 0, 0));
this->nodes.push_back(Node(14, "", 6, 0, 1));
this->nodes.push_back(Node(15, "", 7, 0, 0));
this->nodes.push_back(Node(16, "", 7, 0, 1));
this->nodes.push_back(Node(17, "", 8, 0, 0));
this->nodes.push_back(Node(18, "", 8, 0, 1));
this->nodes.push_back(Node(19, "", 9, 0, 0));
this->nodes.push_back(Node(20, "", 9, 0, 1));
// +++ MEMBERS +++
this->members.push_back(Member(1, "E1", 1, 2, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(2, "E2", 3, 4, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(3, "E3", 5, 6, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(4, "E4", 7, 8, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(5, "E5", 9, 10, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(6, "E6", 11, 12, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(7, "E7", 13, 14, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(8, "E8", 15, 16, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(9, "E9", 17, 18, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(10, "E10", 19, 20, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
// +++ RESTRAINTS +++
// +++ LOAD CASES +++
std::vector <double> massMatrixVector;
// +++ LOADS +++
std::vector <double> loadVector;
};