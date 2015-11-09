#include "stdafx.h"
#include "Model.h"
Model::Model() {
// +++ NODES +++
this->nodes.push_back(Node(1, "", 0, 3, 0));
this->nodes.push_back(Node(2, "", 0, 3, 5));
this->nodes.push_back(Node(3, "", 0, 0, 0));
this->nodes.push_back(Node(4, "", 0, 0, 5));
this->nodes.push_back(Node(5, "", 5, 0, 0));
this->nodes.push_back(Node(6, "", 5, 0, 5));
// +++ MEMBERS +++
this->members.push_back(Member(1, "", 1, 2, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(2, "", 3, 4, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(3, "", 5, 6, 0, 0, 90, "STZ RUSSIAN p_wide_h 5 TMP 1.2e-005"));
this->members.push_back(Member(4, "", 4, 6, 0, 0, 0, "STZ RUSSIAN p_norm_b 14 TMP 1.2e-005"));
this->members.push_back(Member(5, "", 2, 4, 0, 0, 0, "STZ RUSSIAN p_norm_b 14 TMP 1.2e-005"));
this->members.push_back(Member(6, "", 3, 6, 56, 48, 0, "STZ RUSSIAN diam 390 TMP 1.2e-005"));
this->members.push_back(Member(7, "", 2, 3, 48, 56, 0, "STZ RUSSIAN diam 390 TMP 1.2e-005"));
};