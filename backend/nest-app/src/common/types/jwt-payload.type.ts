export type JwtPayload = {
  sub: number;
  role: string;
  agencyId?: number | null;
  email: string;
};
