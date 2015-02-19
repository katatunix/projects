#ifndef _AUDIO_H_
#define _AUDIO_H_

#include <vmsys.h>

typedef struct
{
	VMUINT8*	buffer;
	VMINT		length;
	VMINT		handle;
} Audio;

void loadAudio(VMCHAR* fileName, Audio* audio);

void freeAudio(Audio* audio);

void audioCallback(VMINT handle, VMINT event);

void playAudio(Audio* audio, VMINT timeRepeat);
void stopAudio(Audio* audio);

void initSound();
void freeSound();

void playMusic();
void stopMusic();

void playSfxMove();
void stopSfxMove();

void playSfxScore();
void stopSfxScore();

#endif
