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
this->nodes.push_back(Node(7, "", 0, 0, 0.5));
this->nodes.push_back(Node(8, "", 0, 0, 1));
// +++ MEMBERS +++
this->members.push_back(Member(9, "", 8, 7, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(12, "", 8, 7, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(14, "", 8, 7, 0, 0, 90, "STZ RUSSIAN p_wide_h 5 TMP 1.2e-005"));
this->members.push_back(Member(4, "", 4, 6, 0, 0, 0, "STZ RUSSIAN p_norm_b 14 TMP 1.2e-005"));
this->members.push_back(Member(5, "", 2, 4, 0, 0, 0, "STZ RUSSIAN p_norm_b 14 TMP 1.2e-005"));
this->members.push_back(Member(6, "", 7, 6, 56, 48, 0, "STZ RUSSIAN diam 390 TMP 1.2e-005"));
this->members.push_back(Member(7, "", 8, 2, 56, 48, 0, "STZ RUSSIAN diam 390 TMP 1.2e-005"));
this->members.push_back(Member(9, "", 8, 7, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(9, "", 8, 7, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(12, "", 8, 7, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(12, "", 8, 7, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(12, "", 8, 7, 0, 0, 0, "STZ RUSSIAN p_col_k 1 TMP 1.2e-005"));
this->members.push_back(Member(14, "", 8, 7, 0, 0, 90, "STZ RUSSIAN p_wide_h 5 TMP 1.2e-005"));
this->members.push_back(Member(14, "", 8, 7, 0, 0, 90, "STZ RUSSIAN p_wide_h 5 TMP 1.2e-005"));
};