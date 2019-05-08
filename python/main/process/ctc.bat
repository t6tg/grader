@echo off
python %1.py < %2_1.in > %3.sol 
python %1.py < %2_2.in >> %3.sol 
python %1.py < %2_3.in >> %3.sol 
python %1.py < %2_4.in >> %3.sol 
python %1.py < %2_5.in >> %3.sol 
exit