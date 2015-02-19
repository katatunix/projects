#include "vmsys.h"
#include "vmio.h"
#include "vmgraph.h"
#include "vmchset.h"
#include "vmstdlib.h"
#include <vmres.h>
#include <vmmm.h>

#include "Audio.h"

#define MUSIC_FILE			"music.mid"
#define SFX_MOVE_FILE		"move.mid"
#define SFX_SCORE_FILE		"score.mid"

Audio music, sfxMove, sfxScore;

void loadAudio(VMCHAR* fileName, Audio* audio)
{
	VMINT size;
	VMUINT8* res ;
	if (res = vm_load_resource(fileName, &size))
	{
		audio->buffer = res;
		audio->length = size;
	}
}

void freeAudio(Audio* audio)
{
	vm_midi_stop(audio->handle);
	if (audio->buffer)
	{
		vm_free(audio->buffer);
	}
	audio->buffer = NULL;
	audio->length = 0;
}

void audioCallback(VMINT handle, VMINT event)
{
}

void playAudio(Audio* audio, VMINT timeRepeat)
{
	vm_midi_stop(audio->handle);
	audio->handle = vm_midi_play_by_bytes(audio->buffer, audio->length, timeRepeat, audioCallback); 
}

void stopAudio(Audio* audio)
{
	vm_midi_stop(audio->handle);
}

void initSound()
{
	loadAudio(MUSIC_FILE, &music);
	loadAudio(SFX_MOVE_FILE, &sfxMove);
	loadAudio(SFX_SCORE_FILE, &sfxScore);
}

void freeSound()
{
	freeAudio(&music);
}

void playMusic()
{
	playAudio(&music, 0);
}

void stopMusic()
{
	stopAudio(&music);
}

void playSfxMove()
{
	playAudio(&sfxMove, 1);
}

void stopSfxMove()
{
	stopAudio(&sfxMove);
}

void playSfxScore()
{
	playAudio(&sfxScore, 1);
}

void stopSfxScore()
{
	stopAudio(&sfxScore);
}
