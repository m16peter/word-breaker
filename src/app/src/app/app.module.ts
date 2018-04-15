import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';

import { AppComponent } from './app.component';
import { AppService } from './app.service';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule.withServerTransition({ appId: 'translate-app' }),
    HttpClientModule
  ],
  providers: [
    AppService
  ],
  bootstrap: [
    AppComponent
  ]
})

export class AppModule
{}
