#ifndef _GS_MAINMENU_H_
#define _GS_MAINMENU_H_

void pushMainMenu();

void enterMainMenu();
void updateMainMenu();
void leaveMainMenu();
void destroyMainMenu();
void resumeMainMenu();

void initMainMenu();

//==================================================================================

void startEffect(VMINT itemNumber, VMINT* itemEffect);
void renderMenu(VMINT itemNumber, VMCHAR** itemTextList, VMINT* itemEffect, VMINT curHL);
void updateEffect(VMINT itemNumber, VMINT* itemEffect);
void updateHL(VMINT itemNumber, VMINT* cur);
void renderBackButton();

void renderBubble();
void initBubble();
void updateBubble();

//==================================================================================

#endif
