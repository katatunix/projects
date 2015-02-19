#include "Utils.h"

void loadImage( Image* p, char* filename )
{
	VMINT size;
	VMUINT8* res;
	struct frame_prop* prop;

	p->handle = (VMINT)NULL;
	p->buffer = NULL;
	if( ( res = vm_load_resource( filename, &size ) ) != NULL )
	{
		if( ( p->handle = vm_graphic_load_image( res, size ) ) >= 0 )
		{		
			p->buffer = vm_graphic_get_canvas_buffer( p->handle );
			prop = vm_graphic_get_img_property( p->handle, 1);
			p->width = prop->width;
			p->height = prop->height;
		}	
	}	
	vm_free( res );
}

void unloadImage( Image* p )
{
	if( p->handle )
	{
		vm_graphic_release_canvas( p->handle );	
	}
	p->handle = (VMINT)NULL;
	p->buffer = NULL;
}
