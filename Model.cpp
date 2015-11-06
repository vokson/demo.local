#include "stdafx.h"
#include "Model.h"
Model::Model() {
this->nodes.push_back(Node(1,"Node 1",0,3,0));
this->nodes.push_back(Node(2,"",0,0,0));
this->nodes.push_back(Node(3,"",5,0,0));
this->nodes.push_back(Node(4,"",0,0,5));
this->nodes.push_back(Node(5,"Best Node",0,0,5));
this->nodes.push_back(Node(6,"",0,0,0));
this->nodes.push_back(Node(7,"",0,0,0));
};