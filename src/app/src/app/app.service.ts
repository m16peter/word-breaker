import 'rxjs/add/operator/map';
import { Observable } from "rxjs/Rx";
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Injectable()
export class AppService
{

  constructor(private http: HttpClient)
  {}

  public get(link: string): any
  {
    return this.http.get<any>(link).map(response => response || {});
  }

  public post(link: string, data: any): any
  {
    return new Promise((resolve, reject) =>
    {
      this.http
        .post<any>(link, data)
        .map(response => response || {})
        .catch((err: Response) =>
        {
          reject((err.statusText || "Can't join the server."));
          return Observable.throw(err);
        })
        .subscribe(response =>
        {
          resolve(response);
        });
    });
  }
}
