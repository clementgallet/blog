+++
author = "Clément Gallet"
date = "2016-02-14T12:37:57+01:00"
description = ""
tags = ["4x4x4"]
title = "Algorithmes 4x4x4 PLL+p"
topics = ["rubik"]

+++

Lorsqu'il reste sur un 4x4x4 uniquement à permuter les pièces du dernier étage, on a une chance sur deux de tomber sur des cas qui n'existent pas sur le 3x3x3 et qui requièrent de tourner les tranches du milieu. Généralement, on résout ces cas en échangeant deux arêtes opposées avec la séquence [2R2 U2 2R2 u2 2R2 2u2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=2R2_U2_2R2_u2_2R2_u2) et on peut terminer avec un cas connu.

Déjà en 2006, on avait recherché des séquences pour chacun de ces cas. Frédérick Badie avait regroupé toutes les séquences sur [son site](http://frederickbadie.free.fr/444PLLparity.html). J'avais trouvé quelques séquences en codant un programme assez naïf de résolution de 4x4x4, qui explorait l'arbre de recherche à la fois à partir du mélange et de la position résolue, et recherchait des intersections entre ces deux arbres. Cela permettait de trouver des séquences pour des cas assez simples.

Depuis 2011, j'ai travaillé par intermittence sur un autre [solveur](https://github.com/clementgallet/FiveStage444) de 4x4x4 plus sophistiqué, utilisant cinq étapes de résolution. J'ai publié les descriptions de chaque étape dans ce [post](http://cubezzz.dyndns.org/drupal/?q=node/view/543). Récemment, ces cas de permutation du 4x4x4 ainsi que d'autres ont été publié sur une [page wiki](https://www.speedsolving.com/wiki/index.php/4x4x4_Parity_Algorithms) de speedsolving.com. J'ai alors regardé ce que donnait mon programme avec ces cas du 4x4x4. Il y a plusieurs séquences à retenir pour chaque position, suivant la manière de compter les mouvements :

- SSTM (Single Slice Turn Metric) : chaque mouvement d'une tranche simple compte pour un mouvement
- SSQTM (Single Slice Quarter Turn Metric) : chaque quart de tour d'une tranche simple compte pour un mouvement
- BTM (Block Turn Metric) : chaque mouvement d'un ensemble consécutif de tranches compte pour un mouvement
- BQTM (Block Quarter Turn Metric) : chaque quart de tour d'un ensemble consécutif de tranches compte pour un mouvement

S'il existe une séquence connue qui donne un meilleur résultat que ce que j'ai trouvé, j'indique la séquence avec l'auteur entre crochets. CH : Chris Hardwick ; CM : Christopher Mowla ; FB : Frédérick Badie ; AO : Alexander Ooms ; PKF : Per Kristen Fredlund.

##### PLL opposite dedges

{{% sidefig src="/images/pllp/pllp-oppd.gif" %}}
- [2R2 U2 2R2 u2 2R2 2U2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=2R2_U2_2R2_u2_2R2_2U2) (7s, 14sq, 6b, 12bq) **[CH]**
{{% /sidefig %}}

##### PLL adjacent dedges

{{% sidefig src="/images/pllp/pllp-adjd.gif" %}}
- [R2 D' 2R2 F2 2R2 f2 2R2 2F2 D R2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R2_D-_2R2_F2_2R2_f2_2R2_2F2_D_R2) (11s, 20sq, 10b, 18bq) **[??]**
- [R' F 2L e F2 e' 2L' 2R' e F2 e' 2R F' R](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R-_F_2L_e_F2_e-_2L-_2R-_e_F2_e-_2R_F-_R) (18s, 20sq, 14b, 16bq) **[CM]**
{{% /sidefig %}}

##### PLL O
{{% sidefig src="/images/pllp/pllp-o.gif" %}}
- [2F2 u2 2R2 U' 2R2 2F2 U 2R2 2F2 u2 2F2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=2F2_u2_2R2_U-_2R2_2F2_U_2R2_2F2_u2_2F2) (13s, 24sq, 11b, 20bq)
- [m2 U' m2 u 2U 2F2 m2 d2 2B2 U2 2B2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=m2_U-_m2_u_2U_2F2_m2_d2_2B2_U2_2B2) (15s, 28sq, 11b, 19bq)
{{% /sidefig %}}

##### PLL W
{{% sidefig src="/images/pllp/pllp-w.gif" %}}
- [F2 D' 2F2 2D2 2B2 D' B2 D 2D2 F2 B2 U F2 2D2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=F2_D-_2F2_2D2_2B2_D-_B2_D_2D2_F2_B2_U_F2_2D2) (D2) (14s, 24sq, 13b, 21bq)
- [s2 U' 2R2 2F2 r2 2B2 R2 2B2 u 2U s2 2U2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=s2_U-_2R2_2F2_r2_2B2_R2_2B2_u_2U_s2_2U2) (15s, 28sq, 12b, 21bq)
- [F2 u' 2U' m2 U' F2 D s2 d 2D b2 2U2 2B2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=F2_u-_2U-_m2_U-_F2_D_s2_d_2D_b2_2U2_2B2) (U2) (16s, 28sq, 13b, 20bq)
{{% /sidefig %}}

##### PLL adjacent corners

{{% sidefig src="/images/pllp/pllp-adjc.gif" %}}
- [B2 L2 F' D' F L2 B' U b' 2B' R2 2B2 r2 2B2 2R2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=B2_L2_F-_D-_F_L2_B-_U_b-_2B-_R2_2B2_r2_2B2_2R2) (U') (16s, 26sq, 15b, 23bq)
{{% /sidefig %}}

##### PLL opposite corners

{{% sidefig src="/images/pllp/pllp-oppc.gif" %}}
- [2R2 F2 L' U2 l2 F2 R' D2 R2 U2 B2 R D2 B2 r2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=2R2_F2_L-_U2_l2_F2_R-_D2_R2_U2_B2_R_D2_B2_r2) (U2) (17s, 31sq, 15b, 27bq)
- [F2 L' U2 L R U2 R' F2 l2 F2 U2 l 2L U2 F2 l2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=F2_L-_U2_L_R_U2_R-_F2_l2_F2_U2_l_2L_U2_F2_l2) (U2) (18s, 31sq, 16b, 26bq)
- [r' 2R' s2 U R U' B2 D L' D l 2L D2 F2 l 2L](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=r-_2R-_F2_L_U_L-_B_D_L_D-_B2_U_B_2R2_U2_F2_r_2R) (D2) (17s, 25sq, 16b, 20bq) **[FB]**
{{% /sidefig %}}

##### PLL D

{{% sidefig src="/images/pllp/pllp-d.gif" %}}
- [R2 U' 2F2 D R2 D L2 D' R2 D 2B2 L2 D2 2F2 U R2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R2_U-_2F2_D_R2_D_L2_D-_R2_D_2B2_L2_D2_2F2_U_R2) (U) (16s, 26sq, 16b, 26bq)
- [F2 m2 u 2U F2 U' R2 u 2U R2 U F2 d2 R2 F2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=F2_m2_u_2U_F2_U-_R2_u_2U_R2_U_F2_d2_R2_F2) (D) (17s, 30sq, 15b, 24bq)
{{% /sidefig %}}

##### PLL K

{{% sidefig src="/images/pllp/pllp-k.gif" %}}
- [R2 U' R2 B2 D 2D2 L2 D' B2 D 2D2 B2 D L2 2U2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R2_U-_R2_B2_D_2D2_L2_D-_B2_D_2D2_B2_D_L2_2U2) (U2) (15s, 25sq, 15b, 23bq)
- [R U' L U2 R' U l 2L F2 l2 f2 2L2 f2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R_U-_L_U2_R-_U_l_2L_F2_l2_f2_2L2_f2) (U) (16s, 26sq, 13b, 19bq)
{{% /sidefig %}}

##### PLL P

{{% sidefig src="/images/pllp/pllp-p.gif" %}}
- [R2 B2 u2 R2 U' B2 u' 2U' B2 U R2 u' 2U' R2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R2_B2_u2_R2_U-_B2_u-_2U-_B2_U_R2_u-_2U-_R2) (U') (15s, 26sq, 14b, 22bq)
{{% /sidefig %}}

##### PLL Q

{{% sidefig src="/images/pllp/pllp-q.gif" %}}
- [L2 U L2 u2 F2 U' L2 u' 2U' L2 U2 F2 u 2U L2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=L2_U_L2_u2_F2_U-_L2_u-_2U-_L2_U2_F2_u_2U_L2) (16s, 28sq, 15b, 24bq)
{{% /sidefig %}}

##### PLL C

{{% sidefig src="/images/pllp/pllp-c.gif" %}}
- [r2 B2 U2 2R2 U F2 U B2 U' F2 U r2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=r2_B2_U2_2R2_U_F2_U_B2_U-_F2_U_r2) (U') (14s, 24sq, 12b, 20bq)
- [r' 2R' F R' B2 R F' R' U2 2R2 U2 B2 r2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=r-_2R-_F_R-_B2_R_F-_R-_U2_2R2_U2_B2_r2) (U') (14s, 22sq, 13b, 19bq) **[FB]**
{{% /sidefig %}}

##### PLL I

{{% sidefig src="/images/pllp/pllp-i.gif" %}}
- [F2 u2 F2 U' F2 D' L2 B2 U' 2D2 B2 D L2 2U2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=F2_u2_F2_U-_F2_D-_L2_B2_U-_2D2_B2_D_L2_2U2) (U) (15s, 26sq, 14b, 24bq)
- [r2 U' F2 R' D R' D' R U' R' U R U' 2R2 U2 F2 r2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=r2_U-_F2_R-_D_R-_D-_R_U-_R-_U_R_U-_2R2_U2_F2_r2) (19s, 27sq, 17b, 23bq) **[AO]**
{{% /sidefig %}}

##### PLL theta (θ)

{{% sidefig src="/images/pllp/pllp-theta.gif" %}}
- [R2 U B2 u2 R2 U' B2 u' 2U' B2 U2 R2 u 2U R2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R2_U_B2_u2_R2_U-_B2_u-_2U-_B2_U2_R2_u_2U_R2) (U') (16s, 28sq, 15b, 24bq)
- [R' B' R F D' R' D B R f' 2F' R2 2F2 r2 2F2 r2 U R2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R-_B-_R_F_D-_R-_D_B_R_f-_2F-_R2_2F2_r2_2F2_r2_U_R2) (20s, 29sq, 18b, 24bq) **[PKF]**
{{% /sidefig %}}

##### PLL xi (Ξ)

{{% sidefig src="/images/pllp/pllp-xi.gif" %}}
- [R2 U 2U2 L2 R2 D R2 B2 D 2D2 B2 D2 L2 U 2U2 F2 U2 R2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=R2_U_2U2_L2_R2_D_R2_B2_D_2D2_B2_D2_L2_U_2U2_F2_U2_R2) (U) (18s, 32sq, 17b, 27bq)
- [2U 2D R2 D s2 U B2 R2 U R2 u2 F2 D L2 u2 F2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=2U_2D_R2_D_s2_U_B2_R2_U_R2_u2_F2_D_L2_u2_F2) (D) (19s, 32sq, 16b, 26bq)
- [d2 2L' 2R' F' L' B' L F L' R' F' R B R' F' 2L2 F2 l2 b2](http://alg.cubing.net/?puzzle=4x4x4&type=alg&alg=d2_2L-_2R-_F-_L-_B-_L_F_L-_R-_F-_R_B_R-_F-_2L2_F2_l2_b2) (F') (22s, 30sq, 19b, 24bq) **[FB]**
{{% /sidefig %}}

