LOCAL_PATH := $(call my-dir)

OBJ_STRUCTURE	:= obj/local/$(TARGET_ARCH_ABI)

##
include $(CLEAR_VARS)
LOCAL_MODULE := libgata
LOCAL_SRC_FILES := ../../../../Gata/prj/android/$(OBJ_STRUCTURE)/libgata.a

include $(PREBUILT_STATIC_LIBRARY)
##

include $(CLEAR_VARS)

MY_PROJECT_PATH := $(LOCAL_PATH)
LOCAL_PATH := $(MY_PROJECT_PATH)/../../../src

include $(CLEAR_VARS)

LOCAL_MODULE    := libgl2jni
LOCAL_CFLAGS    := -g -Werror -fno-strict-aliasing -fsigned-char
LOCAL_CPPFLAGS := -fpermissive

LOCAL_C_INCLUDES := $(MY_PROJECT_PATH)/../../../../Gata/inc

LOCAL_SRC_FILES :=	_android/jni_game.cpp \
					_android/file_utils.c \
					auto_generated/images_define.cpp \
					auto_generated/sprites_define.cpp \
					Entity/Entity.cpp \
					Entity/Goomba.cpp \
					Entity/InvisibleKiller.cpp \
					Entity/Koopa.cpp \
					Entity/Particle.cpp \
					Entity/Player.cpp \
					Entity/Mushroom.cpp \
					GameState/GS_InGame.cpp \
					GameState/GS_Logo.cpp \
					Tile/Background.cpp \
					Tile/Map.cpp \
					Tile/NormalBrick.cpp \
					Tile/QuestionBrick.cpp \
					Widget/NavButtonWidget.cpp \
					Widget/PlayingButtonWidget.cpp \
					CGame.cpp \
					GameObject.cpp \
					World.cpp
					
LOCAL_LDLIBS    := -llog -lGLESv2
LOCAL_STATIC_LIBRARIES := libgata

include $(BUILD_SHARED_LIBRARY)
